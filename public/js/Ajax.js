/**
 * Para realizar requisições ajax resumidas no sistema
 */
class AjaxRequester {
  get(url, callback) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: url,
        type: "GET",
        success: (response) => {
          if (callback) callback(response);
          resolve(response);
        },
        error: (error) => {
          this.#toastError(error);
          reject(new Error("Error: " + error.status));
        },
      });
    });
  }

  post(url, data, callback) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: data,
        success: (response) => {
          if (callback) callback(response);
          resolve(response);
        },
        error: (error) => {
          this.#toastError(error);
          reject(new Error("Error: " + error.status));
        },
      });
    });
  }

  put(url, data, callback) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: url,
        type: "PUT",
        dataType: "json",
        data: data,
        success: (response) => {
          if (callback) callback(response);
          resolve(response);
        },
        error: (error) => {
          this.#toastError(error);
          reject(new Error("Error: " + error.status));
        },
      });
    });
  }

  delete(url, callback) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: url,
        type: "DELETE",
        success: (response) => {
          if (callback) callback(response);
          resolve(response);
        },
        error: (error) => {
          this.#toastError(error);
          reject(new Error("Error: " + error.status));
        },
      });
    });
  }

  #toastError(error) {
    Toast.fire({
      icon: "question",
      title: "Oops, ocorreu algum problema, tente novamente mais tarde, se o problema persistir, entre em contato!",
    });
    console.log(error);
  }
}

var Ajax = new AjaxRequester();
