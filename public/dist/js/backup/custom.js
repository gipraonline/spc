$(function () {
  "use strict";

  // Feather Icon Init Js
  // feather.replace();

  // $(".preloader").fadeOut();

  // =================================
  // Tooltip
  // =================================
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // =================================
  // Popover
  // =================================
  var popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // increment & decrement
  $(".minus,.add").on("click", function () {
    var $qty = $(this).closest("div").find(".qty"),
      currentVal = parseInt($qty.val()),
      isAdd = $(this).hasClass("add");
    !isNaN(currentVal) &&
      $qty.val(
        isAdd ? ++currentVal : currentVal > 0 ? --currentVal : currentVal
      );
  });

  // ==============================================================
  // Collapsable cards
  // ==============================================================
  $('a[data-action="collapse"]').on("click", function (e) {
    e.preventDefault();
    $(this)
      .closest(".card")
      .find('[data-action="collapse"] i')
      .toggleClass("ti-minus ti-plus");
    $(this).closest(".card").children(".card-body").collapse("toggle");
  });
  // Toggle fullscreen
  $('a[data-action="expand"]').on("click", function (e) {
    e.preventDefault();
    $(this)
      .closest(".card")
      .find('[data-action="expand"] i')
      .toggleClass("ti-arrows-maximize ti-arrows-maximize");
    $(this).closest(".card").toggleClass("card-fullscreen");
  });
  // Close Card
  $('a[data-action="close"]').on("click", function () {
    $(this).closest(".card").removeClass().slideUp("fast");
  });

  // fixed header
  $(window).scroll(function () {
    if ($(window).scrollTop() >= 60) {
      $(".app-header").addClass("fixed-header");
    } else {
      $(".app-header").removeClass("fixed-header");
    }
  });

  // Checkout
  $(function () {
    $(".billing-address").click(function () {
      $(".billing-address-content").hide();
    });
    $(".billing-address").click(function () {
      $(".payment-method-list").show();
    });
  });
});

/*change layout boxed/full */
$(".full-width").click(function () {
  $(".container-fluid").addClass("mw-100");
  $(".full-width i").addClass("text-primary");
  $(".boxed-width i").removeClass("text-primary");
});
$(".boxed-width").click(function () {
  $(".container-fluid").removeClass("mw-100");
  $(".full-width i").removeClass("text-primary");
  $(".boxed-width i").addClass("text-primary");
});

/*Dark/Light theme*/
$(".light-logo").hide();
$(".dark-theme").click(function () {
  $("nav.navbar-light").addClass("navbar-dark");
  $(".dark-theme i").addClass("text-primary");
  $(".light-theme i").removeClass("text-primary");
  $(".light-logo").show();
  $(".dark-logo").hide();
});
$(".light-theme").click(function () {
  $("nav.navbar-light").removeClass("navbar-dark");
  $(".dark-theme i").removeClass("text-primary");
  $(".light-theme i").addClass("text-primary");
  $(".light-logo").hide();
  $(".dark-logo").show();
});

/*Card border/shadow*/
$(".cardborder").click(function () {
  $("body").addClass("cardwithborder");
  $(".cardshadow i").addClass("text-dark");
  $(".cardborder i").addClass("text-primary");
});
$(".cardshadow").click(function () {
  $("body").removeClass("cardwithborder");
  $(".cardborder i").removeClass("text-primary");
  $(".cardshadow i").removeClass("text-dark");
});

$(".change-colors li a").click(function () {
  $(".change-colors li a").removeClass("active-theme");
  $(this).addClass("active-theme");
});

/*Theme color change*/
function toggleTheme(value) {
  $(".preloader").show();
  var sheets = document.getElementById("themeColors");
  sheets.href = value;
  $(".preloader").fadeOut();
}
$(".preloader").fadeOut();

// ==============================================================
//                  VALIDATION
// ==============================================================

$(document).on('click', '#btn_create,#btn_update', function () {

  var error = 0;
  $('.mandatory').each(function (index, obj) {

    //  if(obj.value==''){
    /*
                      Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: $(obj).data('message'),
                      }) */
    //    $(this).siblings(".text-danger").text($(this).data('message'));
    //    error=1;
    //    return false;

    // }

    if ($(this).val() == '') {
      $(this).siblings(".text-danger").text($(this).data('message'));
      error = 1;
      return false;
    }
  });
  if (error == 0) {
    $("#frm_create").submit();
  }
});


// ==============================================================
//                      ONLOAD VALIDATION
// ==============================================================

$(document).on('click', '#btn_create,#btn_update', function () {

  var error = 0;

  $('.mandatory').each(function () {

    if ($(this).val() == '') {
      $(this).siblings(".text-danger").text($(this).data('message'));
      error = 1;
      return false;
    }

  });

  if (error == 0) {
    $("#frm_create").submit();
  }
});

// ==============================================================
//                      LINK TO STORES
// ==============================================================

document.addEventListener('DOMContentLoaded', function () {
  const designationSelect = document.getElementById('n_designation_id');
  const clusterContainer = document.getElementById('cluster_stores_container');

  function toggleClusterStores() {
    if (!designationSelect || !clusterContainer) return;

    const selectedText = designationSelect.options[designationSelect.selectedIndex].text;

    if (selectedText.trim().toUpperCase().includes('CLUSTER')) {
      clusterContainer.style.display = 'block';
    } else {
      clusterContainer.style.display = 'none';
      $('#cluster_stores').val(null).trigger('change');
    }
  }

  if (designationSelect) {
    designationSelect.addEventListener('change', toggleClusterStores);
    toggleClusterStores();
  }
});

$(document).ready(function () {

  $('#cluster_stores').select2({
    placeholder: "Select Stores",
    width: '100%',
    closeOnSelect: false,
    allowClear: true
  });
});

// ==============================================================
//            Auto Suggest  Store List JavaScript (Limit 5)
// ==============================================================

document.addEventListener('DOMContentLoaded', function () {

  const input = document.getElementById('store_search');
  const results = document.getElementById('store_results');
  const hiddenInput = document.getElementById('n_store_id');
  const container = document.getElementById('store_div');

  // stop if input not found
  if (!input || !results || !hiddenInput) return;

  input.addEventListener('input', function () {

    let query = this.value.trim().toLowerCase();
    results.innerHTML = '';

    // check stores exists
    if (!query || !window.stores) return;

    let filtered = window.stores
      .filter(store => store.c_store_name.toLowerCase().includes(query))
      .slice(0, 5);

    if (filtered.length === 0) {
      results.innerHTML = '<li class="list-group-item">No results found</li>';
      return;
    }

    filtered.forEach(store => {
      let li = document.createElement('li');
      li.className = 'list-group-item list-group-item-action';
      li.style.cursor = 'pointer';
      li.textContent = store.c_store_name;

      li.addEventListener('click', function () {
        input.value = store.c_store_name;
        hiddenInput.value = store.n_store_id;
        results.innerHTML = '';
      });

      results.appendChild(li);
    });

  });

  //dropdown close
  document.addEventListener('click', function (e) {
    if (container && !container.contains(e.target)) {
      results.innerHTML = '';
    }
  });

});

// ==============================================================
//   Auto Suggest for linked Stores(If Designation is Cluster---CREATE)
// ==============================================================
document.addEventListener('DOMContentLoaded', function () {

  const designation = document.getElementById('n_designation_id');

  const storeDiv = document.getElementById('store_div');
  const storeInput = document.getElementById('n_store_id');
  const storeSearch = document.getElementById('store_search');

  const poolDiv = document.getElementById('operations_pool_div');
  const poolSelect = document.getElementById('n_pool_id');

  function handleDesignationChange() {

    if (!designation) return;

    const option = designation.options[designation.selectedIndex];
    const designName = option ? option.text.trim().toUpperCase() : '';
    const storeRequired = option?.getAttribute('data-store') === "1";



    // STORE RESET
    if (storeDiv) {
      storeDiv.style.opacity = '1';
      storeDiv.style.pointerEvents = 'auto';
    }

    if (storeSearch) storeSearch.disabled = false;

    storeSearch?.classList.remove('mandatory');

    // POOL RESET
    if (poolDiv) poolDiv.style.display = 'none';
    poolSelect?.classList.remove('mandatory');


    /* ======================================================
     * STORE RULE
     * ====================================================== */
    if (storeRequired) {

      storeSearch?.classList.add('mandatory');

    } else {

      // disable store cleanly
      if (storeDiv) {
        storeDiv.style.opacity = '0.5';
        storeDiv.style.pointerEvents = 'none';
      }

      if (storeSearch) {
        storeSearch.value = '';
        storeSearch.disabled = true;
      }

      if (storeInput) storeInput.value = '';
    }


    /* ======================================================
     * OPERATIONS RULE
     * ====================================================== */
    if (designName === 'OPERATIONS') {
      if (poolDiv) poolDiv.style.display = 'block';
      poolSelect?.classList.add('mandatory');
    }

  }

  designation?.addEventListener('change', handleDesignationChange);

  // run on load (important for edit page)
  handleDesignationChange();

});


// ==============================================================
// Auto Suggest for linked Stores(If Designation is Cluster---EDIT)
// ============================================================== 
document.addEventListener('DOMContentLoaded', function () {

  const input = document.getElementById('cluster_store_search');
  const results = document.getElementById('cluster_store_results');
  const selectedBox = document.getElementById('selected_cluster_stores');
  const hiddenBox = document.getElementById('cluster_hidden_inputs');

  if (!input || !results || !selectedBox || !hiddenBox) return;

  const selectedStores = new Set();

  function isAlreadyAdded(id) {
    return selectedStores.has(id) ||
      hiddenBox.querySelector(`input[value="${id}"]`);
  }

  function addStore(store) {

    if (isAlreadyAdded(store.n_store_id)) return;

    selectedStores.add(store.n_store_id);

    const tag = document.createElement('span');
    tag.className = 'badge bg-primary d-flex align-items-center gap-1';

    const name = document.createElement('span');
    name.textContent = store.c_store_name;

    const remove = document.createElement('span');
    remove.textContent = '×';
    remove.style.cursor = 'pointer';

    remove.onclick = function () {
      selectedStores.delete(store.n_store_id);
      tag.remove();

      hiddenBox.querySelector(`input[value="${store.n_store_id}"]`)?.remove();
    };

    tag.appendChild(name);
    tag.appendChild(remove);

    selectedBox.appendChild(tag);

    // ---------------- HIDDEN INPUT ----------------
    const hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = 'cluster_stores[]';
    hidden.value = store.n_store_id;

    hiddenBox.appendChild(hidden);
  }


  /* ======================================================
   * PRELOAD
   * ====================================================== */
  if (window.preselectedClusterStores && window.clusterStores) {

    const uniqueIds = [...new Set(window.preselectedClusterStores)];

    uniqueIds.forEach(id => {

      const store = window.clusterStores.find(
        s => String(s.n_store_id) === String(id)
      );

      if (store) addStore(store);
    });
  }


  /* ======================================================
   * SEARCH
   * ====================================================== */
  input.addEventListener('input', function () {

    const q = this.value.trim().toLowerCase();
    results.innerHTML = '';

    if (!q || !window.clusterStores) return;

    const filtered = window.clusterStores
      .filter(s =>
        s.c_store_name.toLowerCase().includes(q) &&
        !isAlreadyAdded(s.n_store_id)
      )
      .slice(0, 5);

    if (!filtered.length) {
      results.innerHTML = '<li class="list-group-item">No results</li>';
      return;
    }

    filtered.forEach(store => {

      const li = document.createElement('li');
      li.className = 'list-group-item list-group-item-action';
      li.textContent = store.c_store_name;

      li.onclick = function () {
        addStore(store);
        input.value = '';
        results.innerHTML = '';
      };

      results.appendChild(li);
    });

  });


  /* ======================================================
   * CLOSE DROPDOWN
   * ====================================================== */
  document.addEventListener('click', function (e) {
    const container = document.getElementById('cluster_stores_container');
    if (container && !container.contains(e.target)) {
      results.innerHTML = '';
    }
  });

});
/* ======================================================
   *Auto suggest for Employee
   * ====================================================== */

document.addEventListener("DOMContentLoaded", function () {

  const input = document.getElementById("employee_search");
  const employeeId = document.getElementById("employee_id");
  const suggestions = document.getElementById("employee_suggestions");

  if (!input) return;

  input.addEventListener("input", function () {

    let query = input.value.toLowerCase().trim();

    employeeId.value = "";
    suggestions.innerHTML = "";

    if (!query) return;

    if (!window.employees) return;

    let filtered = window.employees
      .filter(emp =>
        emp.c_employee_name.toLowerCase().includes(query) ||
        emp.c_employee_code.toLowerCase().includes(query)
      )
      .slice(0, 5);

    filtered.forEach(emp => {

      let item = document.createElement("li");
      item.className = "list-group-item list-group-item-action";

      item.textContent = emp.c_employee_name + " (" + emp.c_employee_code + ")";

      item.dataset.id = emp.n_employee_id;
      item.dataset.name = emp.c_employee_name;

      suggestions.appendChild(item);
    });
  });

  // click selection
  suggestions.addEventListener("click", function (e) {

    if (e.target.tagName === "LI") {

      input.value = e.target.dataset.name;
      employeeId.value = e.target.dataset.id;

      suggestions.innerHTML = "";

    }
  });

  // close dropdown
  document.addEventListener("click", function (e) {
    if (!input.contains(e.target) && !suggestions.contains(e.target)) {
      suggestions.innerHTML = "";
    }
  });

});