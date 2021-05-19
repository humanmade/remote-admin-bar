# Changelog

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
