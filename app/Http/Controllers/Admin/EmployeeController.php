<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignationMaster;
use App\Models\EmployeeMaster;
use App\Models\PoolMaster;
use App\Models\StoreMaster;
use App\Models\EmployeeEditLog;
use App\Models\OperationCluster;
use App\Models\KycSubmission;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        $query = EmployeeMaster::query()
            ->select([
                'employee_masters.*',
            ])

            // designation
            ->leftJoin('designation_masters as d', 'employee_masters.n_designation_id', '=', 'd.n_designation_id');


            // KYC Submission
           // ->leftjoin('kyc_submissions as kyc','kyc.n_employee_id','=','employee_masters.n_employee_id');



            //Employee search (name OR code)
        if ($request->filled('employee_search')) {
                $search = $request->employee_search;

                $query->where(function ($q) use ($search) {
                    $q->where('employee_masters.c_employee_name', 'LIKE', "%{$search}%")
                        ->orWhere('employee_masters.c_employee_code', 'LIKE', "%{$search}%");
                });
        }

        if ($request->filled('n_designation_id')) {
            $query->where('employee_masters.n_designation_id', $request->n_designation_id);
        }

        /* if ($request->filled('n_store_id')) {
            $query->where('employee_masters.n_store_id', $request->n_store_id);
        } */

        $employees = $query->paginate(15)->appends($request->all());
        $employeesForSearch = EmployeeMaster::select(
        'n_employee_id',
        'c_employee_name',
        'c_employee_code'
    )
    ->get();

        $designations = DesignationMaster::where('c_status', 'Y')->get();
       // $stores = StoreMaster::where('c_store_status', 'Y')->get();
        return view('admin.employees.index', compact('employees', 'designations','employeesForSearch'));
    }

    public function create()
    {
        $designations = DesignationMaster::where('c_status', 'Y')->get();
        //unique cluster for a store
       /*  $assignedStoreIds = DB::table('store_clusters')
            ->pluck('n_store_id')
            ->toArray();

        $clusterStores = StoreMaster::where('c_store_status', 'Y')
            ->whereNotIn('n_store_id', $assignedStoreIds)
            ->get();


        $stores = StoreMaster::where('c_store_status', 'Y')->get();

        $pools = PoolMaster::all();
        $clusterManagers = EmployeeMaster::whereIn('n_designation_id', function ($query) {
            $query->select('n_designation_id')->from('designation_masters')->where('c_designation', 'CLUSTER');
        })->where('c_status', 'Y')->get();
    */

        $operationsUsers = EmployeeMaster::whereIn('n_designation_id', function ($query) {
            $query->select('n_designation_id')->from('designation_masters')->where('c_designation', 'OPERATIONS');
        })->where('c_status', 'Y')->get();

        /*   // For Linked stores Auto Suggest
        $clusterStoresData = $clusterStores->values();
        $clusterIds = old('cluster_stores', []); */

        return view('admin.employees.create', compact('designations','operationsUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'c_employee_code' => 'required|string|unique:employee_masters',
            'c_employee_name' => 'required|string',
            'c_employee_address' => 'nullable|string',
            //'c_employee_email' => 'nullable|email',
            'c_employee_email' => 'nullable|email|unique:employee_masters,c_employee_email|unique:employee_masters,c_username',
            'n_employee_phone' => 'nullable|string',
            'n_designation_id' => 'nullable|exists:designation_masters,n_designation_id',
            'n_store_id' => 'nullable|exists:store_masters,n_store_id',
            'c_status' => 'required|in:Y,N',
            'n_pool_id' => 'nullable|exists:pool_masters,n_pool_id',
            'cluster_stores' => 'nullable|array',
            'cluster_stores.*' => 'exists:store_masters,n_store_id',
            'n_cluster_manager_id' => 'nullable|array',
            'n_cluster_manager_id.*' => 'exists:employee_masters,n_employee_id',
            'n_operation_manager_id' => 'nullable|exists:employee_masters,n_employee_id',
            'account_number' => 'required|string|max:30',
            'ifsc_code'      => 'required|string|max:15',
        ],[

    'c_employee_email.unique' => 'This Email/Username already exists.',

    ]);

        $validated['c_username'] = $request['c_employee_code'] ?? null;
        $validated['c_password'] = Hash::make('Password@123');
        $this->assignPoolByDesignation($validated);

        // Custom validation for specific designations
        if (!empty($validated['n_designation_id'])) {
            $designation = DesignationMaster::find($validated['n_designation_id']);
            if ($designation) {
                $desigName = strtoupper(trim($designation->c_designation));

                // Store is mandatory for CSA, C&A, SM
                if (in_array($desigName, ['CSA', 'C&A', 'SM'])) {
                    if (empty($request->n_store_id)) {
                        return back()->withErrors(['n_store_id' => 'The Assigned Store field is mandatory for ' . $desigName . '.'])->withInput();
                    }
                }

                // Operations Manager is mandatory for Cluster Manager
                if ($desigName === 'CLUSTER') {
                    if (empty($request->n_operation_manager_id)) {
                        return back()->withErrors(['n_operation_manager_id' => 'The Operations Manager field is mandatory for Cluster Managers.'])->withInput();
                    }
                }
            }
        }
        // insert employee and kyc data in transaction

    DB::beginTransaction();

        try {

        $employee = EmployeeMaster::create($validated);
        $employee->kycSubmission()->create([
        'account_number' => $request->account_number,
        'ifsc_code'      => $request->ifsc_code,
        'bank_name'       => null,
        'bank_branch'     => null,
        'document_path'  => '',
        'status'         => 'pending',
        ]);
        $this->syncClusterStores($employee, $validated);
        $this->syncOperationCluster($employee, $validated);

    DB::commit();

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully');
        }
        catch (\Exception $e) {

    DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function show(EmployeeMaster $employee)
    {
        $employee = EmployeeMaster::with('designation', 'store')->get();

        return view('admin.employees.show', compact('employee'));
    }

    public function edit(EmployeeMaster $employee)
    {

        $designations = DesignationMaster::where('c_status', 'Y')->get();
        //unique cluster for a store
        $assignedStoreIds = DB::table('store_clusters')
            ->where('n_employee_id', '!=', $employee->n_employee_id)
            ->pluck('n_store_id')
            ->toArray();


        $clusterStores = StoreMaster::where('c_store_status', 'Y')
            ->whereNotIn('n_store_id', $assignedStoreIds)
            ->get();

        $stores = StoreMaster::where('c_store_status', 'Y')->get();

        $pools = PoolMaster::all();
        $clusterManagers = EmployeeMaster::whereIn('n_designation_id', function ($query) {
            $query->select('n_designation_id')->from('designation_masters')->where('c_designation', 'CLUSTER');
        })->where('c_status', 'Y')->get();

        $operationsUsers = EmployeeMaster::whereIn('n_designation_id', function ($query) {
            $query->select('n_designation_id')->from('designation_masters')->where('c_designation', 'OPERATIONS');
        })->where('c_status', 'Y')->get();

        $operationManager = OperationCluster::where('n_cluster_manager_id', $employee->n_employee_id)->latest()->first();
        // dd( $operationManager);
        //dd($operationManager->toSql(), $operationManager->getBindings());
        // For Linked stores Auto Suggest
        $clusterStoresData = $clusterStores->values();
        $clusterIds = old('cluster_stores', $employee->clusters->pluck('n_store_id')->toArray());
        $kyc = $employee->kycSubmission;
// {{ dd($clusterStoresData); }}
        return view('admin.employees.edit', compact('employee', 'designations', 'stores', 'clusterStores', 'pools', 'clusterManagers', 'operationsUsers', 'operationManager','clusterStoresData','clusterIds','kyc'));
    }

    public function update(Request $request, EmployeeMaster $employee)
    {
        //dd($request->all());
        $validated = $request->validate([
            'c_employee_name' => 'required|string',
            'c_employee_address' => 'nullable|string',
            'c_employee_email' => 'nullable|email',
            'n_employee_phone' => 'nullable|string',

            'n_designation_id' => 'nullable|exists:designation_masters,n_designation_id',
            'n_store_id' => 'nullable|exists:store_masters,n_store_id',
            'c_status' => 'required|in:Y,N',
            'n_pool_id' => 'nullable|exists:pool_masters,n_pool_id',
            'cluster_stores.*' => 'exists:store_masters,n_store_id',
            'n_cluster_manager_id' => 'nullable|array',
            'n_cluster_manager_id.*' => 'exists:employee_masters,n_employee_id',
            'n_operation_manager_id' => 'nullable|exists:employee_masters,n_employee_id',

            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()      // at least one alphabet
                    ->numbers()      // at least one number
                    ->symbols(),     // at least one special character
            ],
            [
                'password.min' => 'Password must be at least 8 characters.',
            ]

        ]);

        //emplyee draft log data entry
        $emploeedeslog = EmployeeEditLog::create([
            'n_employee_id' => $employee->n_employee_id,
            'n_pre_designation_id' => $employee->n_designation_id,
            'n_new_designation_id' => $request->n_designation_id,
        ]);


        // Capture n_pool_id explicitly if it's not being placed into the array properly
        // wait, $request->validate() will include it if it's there.
        $validated['n_pool_id'] = $request->input('n_pool_id');
        $validated['n_designation_id'] = $request->input('n_designation_id');
        $validated['cluster_stores'] = $request->input('cluster_stores');
        $validated['n_cluster_manager_id'] = $request->input('n_cluster_manager_id');
        $validated['n_operation_manager_id'] = $request->input('n_operation_manager_id');

        // Only set password if not already set
        if (!empty($request->password)) {
            $validated['c_password'] = Hash::make($request->password);
        } elseif (empty($employee->c_password)) {
            $validated['c_password'] = Hash::make('Password@123');
        }

        $this->assignPoolByDesignation($validated);

        // Custom validation for specific designations
        if (!empty($validated['n_designation_id'])) {
            $designation = DesignationMaster::find($validated['n_designation_id']);
            if ($designation) {
                $desigName = strtoupper(trim($designation->c_designation));

                // Store is mandatory for CSA, C&A, SM
                if (in_array($desigName, ['CSA', 'C&A', 'SM'])) {
                    if (empty($request->n_store_id)) {
                        return back()->withErrors(['n_store_id' => 'The Assigned Store field is mandatory for ' . $desigName . '.'])->withInput();
                    }
                }

                // Operations Manager is mandatory for Cluster Manager
                if ($desigName === 'CLUSTER') {
                    if (empty($request->n_operation_manager_id)) {
                        return back()->withErrors(['n_operation_manager_id' => 'The Operations Manager field is mandatory for Cluster Managers.'])->withInput();
                    }
                }
            }
        }

        $employee->update($validated);
        $employee->kycSubmission()->updateOrCreate(
            ['n_employee_id' => $employee->n_employee_id],
            [
                'account_number' => $request->account_number,
                'ifsc_code'      => $request->ifsc_code,
                'document_path'  => '',
            ]
        );

        $this->syncClusterStores($employee, $validated);
        $this->syncOperationCluster($employee, $validated);



        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully');
    }

    public function destroy(EmployeeMaster $employee)
    {
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully');
    }

    private function assignPoolByDesignation(array &$validated)
    {
        if (!empty($validated['n_designation_id'])) {
            $designation = DesignationMaster::find($validated['n_designation_id']);
            if ($designation) {
                $desigName = strtoupper(trim($designation->c_designation));
                if (in_array($desigName, ['CSA', 'C&A', 'SM'])) {
                    $validated['n_pool_id'] = 0;
                    $validated['n_operations_poolid'] = 0;
                } elseif ($desigName === 'OPERATIONS') {
                   // dd($validated['n_pool_id']);
                    // Pool ID is coming from the request, fallback to first matches if empty
                    if (empty($validated['n_pool_id'])) {
                        $pool = PoolMaster::where('c_pool_name', 'like', '%Operations%')->first();
                        if ($pool) {
                            $validated['n_pool_id'] = $pool->n_pool_id;
                        }
                    }
                    $validated['n_operations_poolid'] = 0;
                } elseif (in_array($desigName, ['BM', 'DC', 'HO'])) {
                    $poolNameSearch = '';
                    if ($desigName === 'HO')
                        $poolNameSearch = 'Head Office';
                    elseif ($desigName === 'DC')
                        $poolNameSearch = 'DC';
                    elseif ($desigName === 'BM')
                        $poolNameSearch = 'BM';

                    $pool = PoolMaster::where('c_pool_name', 'like', '%' . $poolNameSearch . '%')->first();
                    if ($pool) {
                        $validated['n_pool_id'] = $pool->n_pool_id;
                        $validated['n_operations_poolid'] = 0;
                    }
                }
            }
        }
    }

    private function syncClusterStores(EmployeeMaster $employee, array $validated)
    {
        if (!empty($validated['n_designation_id'])) {
            $designation = DesignationMaster::find($validated['n_designation_id']);
            if ($designation && strtoupper(trim($designation->c_designation)) === 'CLUSTER') {
                $employee->clusters()->delete();
                if (!empty($validated['cluster_stores']) && is_array($validated['cluster_stores'])) {
                    foreach ($validated['cluster_stores'] as $storeId) {
                        $employee->clusters()->create(['n_store_id' => $storeId]);
                    }
                }
            } else {
                $employee->clusters()->delete();
            }
        }
    }

    private function syncOperationCluster(EmployeeMaster $employee, array $validated)
    {
        if (!empty($validated['n_designation_id'])) {
            $designation = DesignationMaster::find($validated['n_designation_id']);
            if ($designation && strtoupper(trim($designation->c_designation)) === 'CLUSTER') {
                // If it's a Cluster manager, link them to the selected Operations manager
                // We use n_cluster_manager_id as the cluster manager and n_employee_id as the operations manager
                \App\Models\OperationCluster::where('n_cluster_manager_id', $employee->n_employee_id)->delete();

                if (!empty($validated['n_operation_manager_id'])) {
                    \App\Models\OperationCluster::create([
                        'n_employee_id' => $validated['n_operation_manager_id'],
                        'n_cluster_manager_id' => $employee->n_employee_id,
                    ]);
                }
            } else {
                // If the designation changed away from CLUSTER, or it's not CLUSTER,
                // we might want to clean up. But usually n_cluster_manager_id is unique for CLUSTER managers.
                \App\Models\OperationCluster::where('n_cluster_manager_id', $employee->n_employee_id)->delete();
            }
        }
    }
}
