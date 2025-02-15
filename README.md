# wp-simple-contents-control
A simple contents visibility control plugin for WordPress
## Available shortcodes
### [if-logout] contents [/if-logout]
The inner contents are displayed for logged-out users only.
### [if-login] contents [/if-logout]
The inner contents are displayed for logged-in users only.
### [if-user is="foo, bar"] contents [/if-user]
The inner contents are displayed for the user "foo" or "bar" only.
### [if-role is="administrator, editor"] contents [/if-role]
The inner contents are displayed for the users which belongs to "administrator" or "editor" role only.
### [if-group is="group-a, group-b"] contents [/if-group]
The inner contents are displayed for the users which belongs to BuddyPress group "group-a" or "group-b" only.
### [search-form]
Output search form.
