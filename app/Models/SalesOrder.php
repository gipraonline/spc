<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use App\Support\Geo;

class SalesOrder extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'sales_orders';

    protected string $auditEntity = 'Sales order';

    protected array $auditSubjectColumns = ['c_order_no'];

    protected array $auditIgnore = ['franchise_distance_km'];

    use HasFactory;

    /**
     * Keep franchise_distance_km (straight-line km from the order location to the
     * assigned franchise) in sync whenever the order or its franchise changes,
     * whichever screen / import saved it.
     */
    protected static function booted(): void
    {
        static::saving(function (SalesOrder $order) {
            static $hasColumn = null;
            $hasColumn ??= Schema::hasColumn('sales_orders', 'franchise_distance_km');

            if (! $hasColumn) {
                return; // migration not run yet
            }

            if (! $order->isDirty(['latitude', 'longitude', 'nearest_franchise_id']) && $order->franchise_distance_km !== null) {
                return;
            }

            $order->franchise_distance_km = null;

            if (! $order->nearest_franchise_id || ! Geo::valid($order->latitude, $order->longitude)) {
                return;
            }

            $store = StoreMaster::withTrashed()->find($order->nearest_franchise_id);

            if ($store && Geo::valid($store->latitude, $store->longitude)) {
                $order->franchise_distance_km = round(Geo::distanceKm(
                    (float) $order->latitude, (float) $order->longitude,
                    (float) $store->latitude, (float) $store->longitude
                ), 2);
            }
        });
    }

    protected $table = 'sales_orders';

    protected $primaryKey = 'n_sl_no';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'c_order_no',
        'd_date',
        'farm_care_advisor_id',
        'c_customer_type',
        'n_customer_id',
        /* 'c_customer_name',
        'c_customer_email',
        'c_customer_address',
        'n_customer_mobile', */
        'order_type',
        'n_state_id',
        'n_district_id',
        'n_panchayath_id',
        'latitude',
        'longitude',
        'c_mode_of_payment',
        'c_order_status',
        'nearest_franchise_id',
        'franchise_distance_km',
        'payment_status',
        'c_transaction_id',
        'payment_image',
        'booklet_image',

        'n_total_sales_amount',
        'n_product_discount_total',
        'n_total_gst',
        'n_total_discount',
        'n_net_sales_amount',

        'invoice_no',
        'created_by',
    ];

    protected $casts = [
        'd_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(
            EmployeeMaster::class,
            'farm_care_advisor_id',
            'n_employee_id'
        );
    }

    // public function customer()
    // {
    //     return $this->hasOne(
    //         CustomerMaster::class,
    //         'n_customer_id',
    //         'n_customer_id'
    //     );
    // }
    public function customer()
    {
        return $this->belongsTo(
            CustomerMaster::class,
            'n_customer_id',
            'n_customer_id'
        );
    }

    public function franchise()
    {
        return $this->belongsTo(
            StoreMaster::class,
            'nearest_franchise_id',
            'n_store_id'
        );
    }

    public function approval()
    {
        return $this->hasOne(SalesApproval::class, 'sales_order_id', 'n_sl_no');
    }

    public function orderProducts()
    {
        return $this->hasMany(
            OrderProduct::class,
            'n_order_id',
            'n_sl_no'
        );
    }

    public function paymentStatusLogs()
    {
        return $this->hasMany(
            PaymentStatusLog::class,
            'sales_order_n_sl_no',
            'n_sl_no'
        );
    }

    public function latestPaymentStatusLog()
    {
        return $this->hasOne(
            PaymentStatusLog::class,
            'sales_order_n_sl_no',
            'n_sl_no'
        )->latestOfMany();
    }

    public static function generateTeleOrderNo()
        {
            $lastOrder = self::where('c_order_no', 'like', 'TL-%')
            ->orderByDesc('n_sl_no')
            ->lockForUpdate()
            ->first();


            if (! $lastOrder) {
                return 'TL-1';
            }

            $lastNumber = (int) str_replace(
                'TL-',
                '',
                $lastOrder->c_order_no
            );

            return 'TL-' . ($lastNumber + 1);


        }
        public static function generateFCOOrderNo()
        {
            $lastOrder = self::where('c_order_no', 'like', 'FCO-%')
            ->orderByDesc('n_sl_no')
            ->lockForUpdate()
            ->first();


            if (! $lastOrder) {
                return 'FCO-1';
            }

            $lastNumber = (int) str_replace(
                'FCO-',
                '',
                $lastOrder->c_order_no
            );

            return 'FCO-' . ($lastNumber + 1);


        }

        public static function generateOfficeAdminOrderNo()
        {
            $lastOrder = self::where('c_order_no', 'like', 'OA-%')
            ->orderByDesc('n_sl_no')
            ->lockForUpdate()
            ->first();

            if (! $lastOrder) {
                return 'OA-1';
            }

            $lastNumber = (int) str_replace('OA-', '', $lastOrder->c_order_no);

            return 'OA-' . ($lastNumber + 1);
        }

        public static function generateFCOrderNo()
        {
            $lastOrder = self::where('c_order_no', 'like', 'FS-%')
            ->orderByDesc('n_sl_no')
            ->lockForUpdate()
            ->first();


            if (! $lastOrder) {
                return 'FS-1';
            }

            $lastNumber = (int) str_replace(
                'FS-',
                '',
                $lastOrder->c_order_no
            );

            return 'FS-' . ($lastNumber + 1);


        }


}
