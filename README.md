## Hybrid Rendering Demo

This is just a proof of concept demo for having client side rendered blocks using a "template" persisted via the `save` function. The template currently uses Mustache for template tags that the server is then able to use to know where to render data.

The idea behind this is that it allows for pre-rendering server side the content served to the frontend when we also want a React app to hydrate using that content to the same components used to render the view in the editor. Typically useful when there is interactivity powered by JavaScript.
