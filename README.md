<table width="100%">
	<tr>
		<td align="left" width="80%">
			<h1>Remote Admin Bar</h1>
      Enables the WordPress admin bar for use on headless or decoupled sites
		</td>
		<td rowspan="2" width="20%">
			<img src="https://hmn.md/content/themes/hmnmd/assets/images/hm-logo.svg" width="200" />
		</td>
	</tr>
	<tr>
		<td>
			Proof of concept only at this point, use at your own risk.
		</td>
	</tr>
</table>

## Requirements

Requires PHP >= 7.3.

## How to use this plugin

Install the plugin in the WordPress project and activate it like any other plugin.

On the client side, require the client side scripts by running

```
npm install @humanmade/remote-admin-bar --save-dev
```

In your client-side app, there are three basic functions exposed, `getAdminBar()`, which returns a promise that resolves with data required to render the admin bar, and `render()` and `refresh()`, which can be called with the resolution of this promise and either render it to the page or refresh an admin bar which has already been rendered on a SPA with updated contents based on the new route.

The getAdminBar function takes two arguments, the site URL of the site to query and an object containing context about the current view. This context argument is parsed just like public query variables in WordPress, so any parameter that can be passed through a URL query string will work here.

As an example, the following request will render an admin bar containing an edit link for post ID 1234:

```
import { getAdminBar, render } from '@humanmade/remote-admin-bar';

getAdminBar( 'https://yoursite.dev', { p: 1234 } ).then( render );

```

## Development Process

The development process follows [the standard Human Made development process](http://engineering.hmn.md/how-we-work/process/development/).

Here's a quick summary:

* Assign issues you're working on to yourself.
* Work on a branch per issue, something like `name-of-feature`. One branch per feature/bug, please.
* File a PR early so it can be used for tracking progress.
* When you're finished, mark the PR for review by labelling with "Review &amp; Merge".
* Get someone to review your code, and assign to them; if no one is around, the project lead () can review.

## Cutting a new release

The process of releasing a new version of this plugin is as follows:

- Update the version numbers in `package.json` and `plugin.php`.
- Add an entry to the CHANGELOG describing the changes.
- Once the PR is merged, push a new tag to this repository. This will trigger a new package version to be pushed to npm.
