/**
 * Destinado a funções e variáveis globais não iterativas com o servidor somente com a aplicação front end
 */

/* Seletores que possuírem no-select2  */
$("select:not('.no-select2'):not('.dataTables_wrapper *'):not('select[multiple]'):not(.notranslate)").select2();
$('select[multiple]:not(.notranslate)').select2({
  allowClear: true,
  closeOnSelect: false,
  scrollAfterSelect: false,
  multiple: true,
});
$("select.notranslate:not('.no-select2'):not('.dataTables_wrapper *'):not('select[multiple]')").select2({ containerCssClass : "notranslate", dropdownCssClass: "notranslate"});
$('select[multiple].notranslate').select2({
  containerCssClass: "notranslate",
  dropdownCssClass: "notranslate",
  allowClear: true,
  closeOnSelect: false,
  scrollAfterSelect: false,
  multiple: true,
});

$('[type="submit"]').click((event) => {
  const el = $(event.currentTarget);
  const elClass = "alreadyClicked";

  if (el.hasClass(elClass)) {
    event.preventDefault();
  } else {
    el.addClass(elClass);
    setTimeout(() => {
      el.removeClass(elClass);
    }, 1000);
  }
});

bsCustomFileInput.init();