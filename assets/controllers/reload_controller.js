import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  connect() {
    console.log("connect");

    if (window.localStorage) {
      if (!localStorage.getItem("reload")) {
        localStorage["reload"] = true;
        window.location.reload();
      } else {
        localStorage.removeItem("reload");
      }
    }
  }
}
