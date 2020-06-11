import { render } from "@wordpress/element";
import PostTitle from "./hybrid-component";

const el = document.getElementById("hydrate-block");
render(<PostTitle {...el.dataset} />, el);
