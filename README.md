# Package Managers ([Download latest release](https://github.com/willfarrell/alfred-pkgman-workflow/releases/latest/download/Package.Managers.alfredworkflow))

Package Repo Search

Quick package/plugin/component (repo) lookup for your favourite package managers. Currently supports *Alcatraz* to *Yeoman Generators*.

## Commands

* `pkgman cleardb`: Clear local database cache files
* `pkgman cachedb`: Update local database cache files

* `alcatraz {query}`: [Cocoa Packages](http://alcatraz.io/)
* `apm {query}`: [Atom Packages](https://atom.io)
* `apt-get {query}`: [Ubuntu Packages](https://apps.ubuntu.com)
* `bower {query}`: [Bower Components](http://bower.io) for JavaScript
* `brew {query}`: [Homebrew Plugins](http://braumeister.org)
* `chef {query}`: [Chef Cookbooks](https://supermarket.chef.io)
* `cocoa {query}`: CocoaPods can be upgraded to CocoaDocs by changing `$apple_docs` to true in the script.
* `composer {query}`: PHP [Composer Packages](https://packagist.org)
* `crates {query}`: [Rust Crates](https://crates.io)
* `docker {query}`: [Docker Images](http://registry.hub.docker.io)
* `dt {query}`: DefinitelyTyped [TypeScript Definitions](http://definitelytyped.org)
* `gems {query}`: [Ruby Gems](http://rubygems.org)
* `gradle {query}`: Java [Gradle Packages](http://www.gradle.org)
* `grunt {query}`: nodeJS task-runner [Grunt Plugins](http://gruntjs.com)
* `gulp {query}`: nodeJS task-runner [Gulp Plugins](http://gulpjs.com)
* `hackage {query}`: Haskell [package archive](https://hackage.haskell.org)
* `hex {query}`: Elixir [Hex Packages](http://hex.pm)
* `maven {query}`: Java [Maven Libraries](http://mvnrepository.com)
* `npm {query}`: nodeJS [NPM Packages](https://www.npmjs.org)
* `nuget {query}`: .Net [NuGet Packages](http://nuget.org)
* `pear {query}`: PHP [Pear Packages](http://pear.php.net)
* `puppet {query}`: [Puppet Modules](https://forge.puppetlabs.com)
* `pypi {query}`: [Python Packages](https://pypi.python.org)
* `r {query}`: [R Packages](https://www.r-pkg.org/)
* `raspbian {query}`: [Rasberry Pi Packages](http://www.raspbian.org)
* `rpm {query}`: [Linux Packages](http://rpmfind.net)
* `st {query}`: [Sublime Text Packages](https://packagecontrol.io)
* `yarn {query}`: [Yarn Packages](https://yarnpkg.com/lang/en/)
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

![][alcatraz]
![][apm]
![][bower]
![][brew]
![][chef]
![][cocoa]
![][composer]
![][crates]
![][docker]
![][gems]
![][grunt]
![][gulp]
![][hex]
![][maven]
![][npm]
![][pear]
![][pypi]
![][r]
![][rpm]
![][st]
![][yo]

Featured on [Smashing Magazine](http://www.smashingmagazine.com/2013/10/25/hidden-productivity-secrets-with-alfred/)

[alcatraz]: ./screenshots/alcatraz.png "Sample alcatraz result"
[apm]: ./screenshots/apm.png "Sample apm result"
[bower]: ./screenshots/bower.png "Sample bower result"
[brew]: ./screenshots/brew.png "Sample brew result"
[chef]: ./screenshots/chef.png "Sample chef result"
[cocoa]: ./screenshots/cocoa.png "Sample cocoa result"
[composer]: ./screenshots/composer.png "Sample composer result"
[crates]: ./screenshots/crates.png "Sample crates result"
[docker]: ./screenshots/docker.png "Sample docker result"
[gems]: ./screenshots/gems.png "Sample gems result"
[grunt]: ./screenshots/grunt.png "Sample grunt result"
[gulp]: ./screenshots/gulp.png "Sample gulp result"
[hex]: ./screenshots/hex.png "Sample hex resuls"
[maven]: ./screenshots/maven.png "Sample maven result"
[npm]: ./screenshots/npm.png "Sample npm result"
[pear]: ./screenshots/pear.png "Sample pear result"
[pypi]: ./screenshots/pypi.png "Sample pypi result"
[r]: ./screenshots/r.png "Sample r result"
[rpm]: ./screenshots/rpm.png "Sample rpm result"
[st]: ./screenshots/stpm.png "Sample stpm result"
[yarn]: ./screenshots/yarn.png "Sample yarn result"
[yo]: ./screenshots/yo.png "Sample yo result"
