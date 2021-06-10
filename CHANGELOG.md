# Changelog

## 0.0.6
- Fix CORS headers for admin bar preflight check. Allows the plugin to work in recent versions of Chrome.
- Handle unauthorized requests with a rejected promise, so that the front end doesn't apply the admin-bar class to the body if the user is not logged in.
- Remove comments when transpiling script, to make the dist package smaller.

## 0.0.5
- Remove usage of es-next syntax which was not transpiled to JS which worked in environments without regenerator runtime.

## 0.0.4
- Add 'remote_admin_bar_menu' hook which can be used to add or remove menu nodes specifically in the remote context.

## 0.0.3
- Set cookie on login which is not http-only, so that the client side library can do an intitial check of the user's logged in state before making admin-ajax requests.
- Add `isLoggedIn` function to the client package which checks for the presense of this cookie.

## 0.0.2
- Properly set context of the admin bar based on any public query cars in the request.
- Add action to publish npm package versions when a new tag is pushed to this repo.

## 0.0.1
- Initial commits and proof of concept.
