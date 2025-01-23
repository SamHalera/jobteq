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
    const btnAdd = document.querySelectorAll(".btn-add");
    const btnRemove = document.querySelectorAll(".btn-remove");

    btnAdd.forEach((btn) => btn.addEventListener("click", this.addNewItemRow));
    btnRemove.forEach((btn) => btn.addEventListener("click", this.itemRemove));
  }

  addNewItemRow = (e) => {
    const collectionHolder = document.querySelector(
      e.currentTarget.dataset.collection
    );
    let index = collectionHolder.dataset.index;

    const prototype = collectionHolder.dataset.prototype;

    const itemRow = document
      .createRange()
      .createContextualFragment(
        prototype.replace(/__name__/g, index)
      ).firstElementChild;

    collectionHolder.insertAdjacentElement("beforeend", itemRow);
    collectionHolder.dataset.index++;
    const btnRemove = document.querySelectorAll(".btn-remove");
    btnRemove.forEach((btn) => btn.addEventListener("click", this.itemRemove));
  };

  itemRemove = (e) => {
    const itemToRemove = e.currentTarget.closest(".item");

    itemToRemove.remove();
  };
}
