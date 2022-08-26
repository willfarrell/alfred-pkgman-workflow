# Package Managers ([Download latest release](https://github.com/willfarrell/alfred-pkgman-workflow/releases/latest/download/Package.Managers.alfredworkflow))

Package Repo Search

Quick package/plugin/component (repo) lookup for your favourite package managers. Currently supports *Alcatraz* to *Yeoman Generators*.

## PATH Variable

With macOS ≥12.3 Monterey no longer providing PHP, it's now a prerequisite to install your own.
Simplest way to install it yourself is via `brew install php`.

If you don’t have Homebrew, you can install it via [instructions on their homepage, brew.sh](https://brew.sh).

The `PATH` variable needs to capture not only where PHP is installed, but also where Bash is. Here are two variations:

* Intel Macs: `/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin`
* Apple Silicon Macs: `/opt/homebrew/bin:/usr/bin:/bin:/usr/sbin:/sbin`

## Commands

### Local Cache Management Commands

* `pkgman cleardb`: Clear local database cache files
* `pkgman cachedb`: Update local database cache files

## Package Repository Search Commands

* `alcatraz {query}`: [Cocoa Packages](http://alcatraz.io/)
* `apt-get {query}`: [Ubuntu Packages](https://apps.ubuntu.com)
* `bower {query}`: [Bower Components](http://bower.io) for JavaScript
* `brew {query}`: [Homebrew Forumale/Cask](http://brew.sh)
* `chef {query}`: [Chef Cookbooks](https://supermarket.chef.io)
* `cocoa {query}`: CocoaPods can be upgraded to CocoaDocs by changing `$apple_docs` to true in the script.
* `composer {query}`: PHP [Composer Packages](https://packagist.org)
* `cordova {query}`: [Apache Cordova plugins](https://cordova.apache.org/plugins/)
* `docker {query}`: [Docker Images](http://registry.hub.docker.io)
* `definitelytyped {query}`: DefinitelyTyped [TypeScript Definitions](http://definitelytyped.org)
* `gems {query}`: [Ruby Gems](http://rubygems.org)
* `gradle {query}`: Java [Gradle Packages](http://www.gradle.org)
* `grunt {query}`: nodeJS task-runner [Grunt Plugins](http://gruntjs.com)
* `gulp {query}`: nodeJS task-runner [Gulp Plugins](http://gulpjs.com)
* `hackage {query}`: Haskell [package archive](https://hackage.haskell.org)
* `hex {query}`: Elixir [Hex Packages](http://hex.pm)
* `maven {query}`: Java [Maven Libraries](http://mvnrepository.com)
* `metacran {query}`: [R Packages](https://www.r-pkg.org/)
* `npm {query}`: nodeJS [NPM Packages](https://www.npmjs.org)
* `nuget {query}`: .Net [NuGet Packages](http://nuget.org)
* `pear {query}`: PHP [Pear Packages](http://pear.php.net)
* `puppet {query}`: [Puppet Modules](https://forge.puppetlabs.com)
* `pypi {query}`: [Python Packages](https://pypi.python.org)
* `raspbian {query}`: [Rasberry Pi Packages](http://www.raspbian.org)
* `rpm {query}`: [Red Hat Linux Packages](http://rpmfind.net)
* `snap {query}`: [Snapcraft Packages](https://snapcraft.io)
* `st {query}`: [Sublime Text Packages](https://packagecontrol.io)
* `yarn {query}`: [Yarn Packages](https://yarnpkg.com/lang/en/)
* `yo {query}`: [Yeoman Generators](http://yeoman.io)

## Action Modifiers

* `default`: open README page url
* `cmd`: copy name/id to frontmost app
* `shift`: copy config file name/id and version to frontmost app

## Additional Notes

All workflows require constant internet connection.

Workflows can break from time to time due to changes by the provider of the repo. If you notice a workflow stops working, please [file an Issue](https://github.com/willfarrell/alfred-pkgman-workflow/issues).

All repos have caching enabled to speed up common queries. These caches are refreshed after 14 days and may take longer than expected to return results during update. You can force a cache refresh by running `pkgman cachedb` to re-download the databases (applies to `alcatraz`, `grunt`, `cocoa`). Alternatively you can run `pkgman cleardb` to remove all stored cache, but this isn't recommended. Clearing your cache is recommended after an update if the package manager you use had a bug previously.

The Python Package Index is very slow due to a lack on API and pagination. A min query length has been put in place to help speed this up. You can change it in the script, `$min_query_length = 3`. Perhaps someone with a python background can improve this.

## Contribution

There is a `Makefile` to automate the contribution steps. This `Makefile` have one prerequisite: you should set `ALFRED_PKGMAN_WORKFLOW_DIR` shell environment variable targeting to workflow path installed in Alfred. To get the path you should:

1. Go Alfred Preferences → Workflows
2. Find “Package Managers”
3. Right click on it and select “Open in Finder”

To make a contribution:

1. Make desired changes to workflow / underlying code base
2. Build the Alfred workflow
3. Prepare a new release:
    1. Update `Package Managers.alfredworkflow` file in the root of repository from your newly-built one
    2. Update `info.plist` in the root of the repository

See below for how to run the automation that handles these steps.

### Make changes to workflow / underlying code base

`make linkSourceFoldersToWorkflow` - links src, bin, vendor folders from your local cloned source code folder of the workflow to corresponding folders that installed in Alfred, located at `ALFRED_PKGMAN_WORKFLOW_DIR`.

### Build the Alfred workflow

`make dist` - runs `composer install`, copies sources to `./dist`, copies icons and `info.plist` from `ALFRED_PKGMAN_WORKFLOW_DIR` to `./dist`, zips `./dist` to `./dist/Package Managers.alfredworkflow`

### Prepare a new release

`make release` - builds a new release by copying `info.plist` and `Package Managers.alfredworkflow` from `./dist` to the root of the repository.

![Alcatraz logo][alcatraz]
![Bower logo][bower]
![Homebrew logo][brew]
![Chef logo][chef]
![CocoaPods logo][cocoa]
![Composer logo][composer]
![Docker logo][docker]
![Rubygems logo][gems]
![Grunt logo][grunt]
![Gulp logo][gulp]
![Hex logo][hex]
![Maven logo][maven]
![Metacran logo][metacran]
![NPM logo][npm]
![PEAR logo][pear]
![PyPi logo][pypi]
![Red Hat fedora logo][rpm]
![Sublime Text logo][st]
![Yarn logo][yarn]
![Yo logo][yo]

Featured on [Smashing Magazine](http://www.smashingmagazine.com/2013/10/25/hidden-productivity-secrets-with-alfred/)

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
[metacran.png]: ./screenshots/metacran.png "Sample metacran.png result"
[npm]: ./screenshots/npm.png "Sample npm result"
[pear]: ./screenshots/pear.png "Sample pear result"
[pypi]: ./screenshots/pypi.png "Sample pypi result"
[rpm]: ./screenshots/rpm.png "Sample rpm result"
[st]: ./screenshots/stpm.png "Sample stpm result"
[yarn]: ./screenshots/yarn.png "Sample yarn result"
[yo]: ./screenshots/yo.png "Sample yo result"
