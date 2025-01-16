import { Controller } from "@hotwired/stimulus";

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
  connect() {
    this.index = this.element.childElementCount;
    const btnAddElement = document.createElement("button");
    btnAddElement.innerHTML = "add";
    btnAddElement.setAttribute("type", "button");
    btnAddElement.setAttribute(
      "class",
      "btn btn-outline-info align-self-center"
    );
    btnAddElement.addEventListener("click", (e) => {
      console.log("Adding item");
      this.addItemRow(e);
    });
    this.element.append(btnAddElement);
  }

  addItemRow = (e) => {
    e.preventDefault();
    const itemRow = document
      .createRange()
      .createContextualFragment(
        this.element.dataset["prototype"].replace(/__name__/g, this.index)
      ).firstElementChild;
    itemRow.setAttribute("class", "bg-light p-4 rounded-2 my-4 shadow-sm");

    this.index++;
    this.createBtnRemoveItem(itemRow);

    e.currentTarget.insertAdjacentElement("beforebegin", itemRow);
  };

  /**
   * @param {HTMLElement} item
   */
  createBtnRemoveItem = (item) => {
    const btnElt = document.createElement("span");
    btnElt.id = item.id;
    btnElt.classList.add("btn", "btn-danger");
    btnElt.textContent = "remove";

    item.appendChild(btnElt);
    btnElt.addEventListener("click", (e) => {
      e.preventDefault();

      item.remove();
    });
    return btnElt;
  };
}
