import { Controller } from "@hotwired/stimulus";
import Swal from "sweetalert2";

export default class extends Controller {
  static values = {
    title: String,
    text: String,
    icon: String,
    confirmButtonText: String,
    submitAsync: Boolean,
  };
  connect() {
    console.log("hello submit");
  }

  /**
   *
   * @param {MouseEvent} event
   */
  onSubmit(event) {
    console.log("onSubmit method");
    event.preventDefault();
    Swal.fire({
      title: this.titleValue || null,
      text: this.textValue || null,
      icon: this.iconValue || null,
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: this.confirmButtonText || "Yes",
      showLoaderOnConfirm: true,
      preConfirm: () => {
        return this.submitForm(this.element.submit());
      },
    });
    // .then((result) => {
    //   if (result.isConfirmed) {
    //     this.element.submit();
    //   }
    // })
  }

  submitForm(element) {
    if (!this.submitAsync) {
      return element.submit();
    }
    return fetch(this.element.action, {
      method: this.element.method,
      body: new URLSearchParams(new FormData(this?.element)),
    });
  }
}
