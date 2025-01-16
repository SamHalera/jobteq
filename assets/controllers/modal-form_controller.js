import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";

export default class extends Controller {
  static targets = ["modal", "modalBody"];
  static values = {
    formUrl: String,
  };

  async openModal() {
    this.modalBodyTarget.innerHTML = "Loading...";
    const modal = new Modal(this.modalTarget);
    modal.show();

    const response = await fetch(this.formUrlValue);
    const template = await response.text();

    this.modalBodyTarget.innerHTML = template;
  }

  async submitForm(event) {
    console.log("hello ici");
    event.preventDefault();
    const params = new URLSearchParams({
      submit: true,
    });

    const form = this.modalBodyTarget.querySelector("form");
    const dataForm = new URLSearchParams(new FormData(form));
    // const dataForm = new FormData(form).toString();
    const url = this.formUrlValue;
    const method = form.method.toUpperCase();
    const body = dataForm.toString();
    console.log(url);
    console.log(body);

    const response = await fetch(`${url}?${params.toString()}`, {
      method,
      body,
    });
    const newHtmlFromResponse = await response.text();
    this.modalBodyTarget.innerHTML = newHtmlFromResponse;
    console.log(newHtmlFromResponse);
    if (response.ok) {
      console.log("Form submitted successfully");
    } else {
      console.error("Failed to submit the form");
    }
  }
}
