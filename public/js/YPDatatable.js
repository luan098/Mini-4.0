/**
 * JqueryHook para gerar datatable com os defaults do sistema
 * !! Para considerar os filtros vocês precisa adicionar YP-datatable-filters no form deles
 * !! deve funcionar normalmente qual qualquer passagem do jquery porem o parâmetro searchable foi alterado para dar maior compatibilidade
 * columns: [{
 *        searchable: { // searchable pode ser um boolean ou um objeto, caso seja um objeto você pode passar o parâmetro regex e escolher um regex para limpar o filtro (somente para o campo que tiver este regex)
 *            regex: '[^0-9]+'
 *        }
 * },
 *
 * @version 0.2.3 Versão protótipo
 * @author @see https://github.com/luan098
 * @param {string} modelName name da model que sera feita a consulta
 * @param {object} options objeto com as options do datatable ver na documentação dele as possibilidades @see https://datatables.net/reference/option/
 * @returns object
 */
jQuery.fn.YPDatatable = function (modelName = "", options = {}) {
  options.buttons = options.buttons?.map((e) => {
    const element = {
      extend: e,
      footer: true,
      title: $("h1").text(),
      autoPrint: false,
      customize: function (win) {
        // if (e == "print") {
        //   if ($("#header-print").length) {
        //     $(win.document.body).find("h1").remove();
        //     $(win.document.body).prepend($("#header-print").clone().removeClass("hidden"));
        //     $(win.document.body).find("img").remove();
        //   } else {
        //     $(win.document.body).prepend($("h1"));
        //   }
        // }
      },
    };

    if (["pdf", "csv", "excel", "print", "pdfHtml5"].includes(e)) {
      element.action = dataTableServerSideExport;
      element.exportOptions = {
        columns: ":visible",
      };
    }

    return element;
  });

  if (options.columns.some((e) => typeof e.metric !== "undefined")) {
    $(this).append("<tfoot><tr></tr></tfoot>");
    options.columns.forEach(() => {
      $(this).find("tfoot tr").append("<td></td>");
    });
  }

  table = $(this).DataTable({
    processing: true,
    serverSide: true,
    autoColumns: true,
    autoWidth: false,
    responsive: true,
    ordering: true,
    bLengthChange: true,
    bInfo: true,
    search: {
      return: true,
    },
    pagingType: "numbers",
    dom:
      "<'row d-flex align-items-end'<'col-sm-6'l><'col-sm-6 text-right'B<'datatable-search-container d-inline-block'f>>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row d-flex align-items-start'<'col-sm-5'i><'col-sm-7'p>>",
    language: {
      lengthMenu: "_MENU_ results",
      search:
        '<div class="custom-search"><div class="search-input-container"><input type="search" class="form-control form-control-sm" aria-label="Search"><label class="search-icon"><i class="fa fa-search"></i></label></div></div>',
      searchPlaceholder: "Search",
    },

    ...options,
    ajax: async (data, callback, settings) => {
      const inputValues = {};
      const inputs = $(".YP-datatable-filters input[name],.YP-datatable-filters select[name]");
      inputs.each((i, e) => {
        if (!e.disabled) {
          const v = $(e).attr("validation") || "=";
          const isAnGroup = e.name.includes("[");

          if (isAnGroup) {
            const cleanName = e.name.substring(0, e.name.indexOf("["));

            inputValues[cleanName] = [...(inputValues[cleanName] ?? []), { value: $(e).val(), validation: v }];
          } else {
            inputValues[e.name] = {
              value: $(e).val(),
              validation: v,
            };
          }
        }
      });

      data.filters = inputValues;

      data.columns.map((e, key) => {
        e.field = settings?.aoColumns[key]?.field ? settings?.aoColumns[key]?.field : e.data;
        e.value = settings?.aoColumns[key]?.value ? settings?.aoColumns[key]?.value : e.field;
        e.join = settings?.aoColumns[key]?.join ? settings?.aoColumns[key]?.join : "";
        e.search.regex = settings?.aoColumns[key]?.search?.regex ? settings?.aoColumns[key]?.search?.regex : "";
      });

      const response = await AjaxDatatable.post(`datatable/${modelName}`, { data });

      callback(response);
    },
  });

  return table;
};

/**
 *  Função que remove a paginação do datatable para realizar a impressão
 *  Ainda está em testes
 *
 * @param {object} e evento
 * @param {object} dt datatables do
 * @param {object} button botão
 * @param {object} config config
 */
function dataTableServerSideExport(e, dt, button, config) {
  var self = this;
  var oldStart = dt.settings()[0]._iDisplayStart;
  dt.one("preXhr", function (e, s, data) {
    // Just this once, load all data from the server...
    data.start = 0;
    data.length = 2147483647;
    dt.one("preDraw", function (e, settings) {
      // Call the original action function
      if (button[0].className.indexOf("buttons-copy") >= 0) {
        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
      } else if (button[0].className.indexOf("buttons-excel") >= 0) {
        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)
          ? $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config)
          : $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
      } else if (button[0].className.indexOf("buttons-csv") >= 0) {
        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config)
          ? $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config)
          : $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
      } else if (button[0].className.indexOf("buttons-pdf") >= 0) {
        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config)
          ? $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config)
          : $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
      } else if (button[0].className.indexOf("buttons-print") >= 0) {
        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
      }
      dt.one("preXhr", function (e, s, data) {
        // DataTables thinks the first item displayed is index 0, but we're not drawing that.
        // Set the property to what it was before exporting.
        settings._iDisplayStart = oldStart;
        data.start = oldStart;
      });
      // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
      setTimeout(dt.ajax.reload, 0);
      // Prevent rendering of the full data to the DOM
      return false;
    });
  });
  // Requery the server with the new one-time export settings
  dt.ajax.reload();
}


/**
 * Classe utilizada para realizar requisições ajax internamente criada para englobar todas as regras e tratamentos de requisições em um só lugar, 
 * trabalha diretamente com AjaxMasterController.php, verificar os arquivos para entender o uso
 */
class AjaxDT {
  constructor() {}

  /**
   * @param {string} endpoint rota para realizar a requisição Exemplo: paymentAgreement/getFilterSupplierForBillReceiveInstallment
   * @param {PlainObject} body
   * @param {PlainObject} headers
   */
  post(endpoint, body = {}, headers = {}) {
      return new Promise((resolve) => {
          const bodyFormatted = this.#formatEntry(body);

          $.post({
              url: `${url}/${endpoint}`,
              dataType: "json",
              data: bodyFormatted,
              headers: headers,
              xhrFields: { withCredentials: true },
              success: (response) => {
                  try {
                      if (response.error) throw new Error(response.message);
                      resolve(response.data);
                  } catch (error) {
                      Toast.fire({
                          icon: "error",
                          title: error.message,
                      });
                  }
              },
              error: (jqXHR) => {
                  Swal.fire({
                      title: "Send this message to our team or try again late.",
                      html:
                          jqXHR?.responseText ||
                          "An Error ocurred during the procedure",
                      type: "error",
                      width: "60vw",
                      showCancelButton: false,
                      confirmButtonText:
                          'Confirmar <i class="fa fa-check"></i>',
                      confirmButtonColor: "#00a65a",
                      reverseButtons: true,
                      allowOutsideClick: false,
                  });
              },
          });
      });
  }

  /**
   * Função #privada, só usar aqui dentro, feita para padronizar os itens enviados para que a requisição envie os valores corretos
   * @param {PlainObject} body
   */
  #formatEntry(body) {
      const bodyFormatted = {};
      for (let prop in body) {
          if (Object.prototype.hasOwnProperty.call(body, prop)) {
              bodyFormatted[prop] = JSON.stringify(body[prop]);
          }
      }

      return bodyFormatted;
  }
}

/* Instância a classe para não ficar instanciando a todo momento */
var AjaxDatatable = new AjaxDT();
