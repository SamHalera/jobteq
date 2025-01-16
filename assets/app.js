import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import { Popover } from "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import "./styles/app.css";

import a2lix_lib from "https://esm.run/@a2lix/symfony-collection/dist/a2lix_sf_collection.min";

a2lix_lib.sfCollection.init();
console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
