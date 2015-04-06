# Package Managers ([Download v3.00](https://raw.github.com/willfarrell/alfred-pkgman-workflow/master/Package%20Managers.alfredworkflow))

Package Repo Search

Quick package/plugin/component (repo) lookup for your favourite package managers. Currently supports *Alcatraz* to *Yeoman Generators*.

## Commands

* `pkgman cleardb`: Clear local database cache files
* `pkgman cachedb`: Update local database cache files

* `alcatraz {query}`: [Cocoa Packages](http://alcatraz.io/)
* `apt-get {query}`: [Linux Packages](https://apps.ubuntu.com)
* `atom {query}`: [Atom Packages](https://atom.io)
* `bower {query}`: [Bower Components](http://bower.io) for JavaScript
* `brew {query}`: [Homebrew Plugins](http://braumeister.org)
* `chef {query}`: [Chef Cookbooks](https://supermarket.chef.io)
* `cocoa {query}`: CocoaPods can be upgraded to CocoaDocs by changing `$apple_docs` to true in the script.
* `composer {query}`: PHP [Composer Packages](https://packagist.org)
* `docker {query}`: [Docker Images](http://registry.hub.docker.io)
* `gems {query}`: [Ruby Gems](http://rubygems.org)
* `gradle {query}`: Java [Gradle Packages](http://www.gradle.org)
* `grunt {query}`: nodeJS task-runner [Grunt Plugins](http://gruntjs.com)
* `gulp {query}`: nodeJS task-runner [Gulp Plugins](http://gulpjs.com)
* `hex {query}`: Elixir [Hex Packages](http://hex.pm)
* `maven {query}`: Java [Maven Libraries](http://mvnrepository.com)
* `npm {query}`: nodeJS [NPM Packages](https://www.npmjs.org)
* `pear {query}`: PHP [Pear Packages](http://pear.php.net)
* `puppet {query}`: [Puppet Modules](https://forge.puppetlabs.com)
* `pypi {query}`: [Python Packages](https://pypi.python.org)
* `raspbian {query}`: [Rasberry Pi Packages](http://www.raspbian.org)
* `rpm {query}`: [Linux Packages](http://rpmfind.net)
* `yo {query}`: [Yeoman Generators](http://yeoman.io)

## Action Modifiers

* `default`: open README page url
* `cmd`: copy name/id to frontmost app
* `shift`: copy config file name/id and version to frontmost app

## Additional Notes

All workflows require constant internet connection.

Workflows can break from time to time due to changes by the provider of the repo. If you notice a workflow stops working, post it at https://github.com/willfarrell/alfred-pkgman-workflow/issues.

All repos have caching enabled to speed up common queries. These caches are refreshed after 14 days and may take longer than expected to return results during update. You can force a cache refresh by running `pkgman cachedb` to re-download the databases (applies to `alcatraz`, `grunt`, `cocoa`). Alternatively you can run `pkgman cleardb` to remove all stored cache, but this isn't recommended. Clearing your cache is recommended after an update if the package manager you use had a bug previously.

The Python Package Index is very slow due to a lack on API and pagination. A min query length has been put in place to help speed this up. You can change it in the script, `$min_query_length = 3`. Perhaps someone with a python background can improve this.

![][bower]
![][npm]
![][yo]
![][grunt]
![][gulp]
![][alcatraz]
![][cocoa]
![][composer]
![][pear]
![][pypi]
![][gems]
![][maven]
![][brew]
![][rpm]
![][docker]
![][chef]
![][hex]

[alcatraz]: ./screenshots/alcatraz.png "Sample alcatraz result"
[bower]: ./screenshots/bower.png "Sample bower result"
[brew]: ./screenshots/brew.png "Sample brew result"
[chef]: ./screenshots/chef.png "Sample chef result"
[cocoa]: ./screenshots/cocoa.png "Sample cocoa result"
[composer]: ./screenshots/composer.png "Sample composer result"
[docker]: ./screenshots/docker.png "Sample docker result"
[gems]: ./screenshots/gems.png "Sample gems result"
[grunt]: ./screenshots/grunt.png "Sample grunt result"
[gulp]: ./screenshots/gulp.png "Sample gulp result"
[hex]: ./screenshots/hex.png "Sample hex resuls"
[maven]: ./screenshots/maven.png "Sample maven result"
[npm]: ./screenshots/npm.png "Sample npm result"
[pear]: ./screenshots/pear.png "Sample pear result"
[pypi]: ./screenshots/pypi.png "Sample pypi result"
[rpm]: ./screenshots/rpm.png "Sample rpm result"
[yo]: ./screenshots/yo.png "Sample yo result"
