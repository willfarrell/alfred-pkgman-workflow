# Package Managers ([Download latest release](https://github.com/willfarrell/alfred-pkgman-workflow/releases/latest/download/Package.Managers.alfredworkflow))

Package Repo Search

Quick package/plugin/component (repo) lookup for your favourite package managers.

[Featured on Smashing Magazine](http://www.smashingmagazine.com/2013/10/25/hidden-productivity-secrets-with-alfred/) in 2013.

## PATH Variable

With macOS ≥12.3 Monterey no longer providing PHP, it's now a prerequisite to install your own.
Simplest way to install it yourself is via `brew install php`.

If you don’t have Homebrew, you can install it via [instructions on their homepage, brew.sh](https://brew.sh).

The `PATH` variable needs to capture not only where PHP is installed, but also where Bash is. Here are two variations:

* Intel Macs: `/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin`
* Apple Silicon Macs: `/opt/homebrew/bin:/usr/bin:/bin:/usr/sbin:/sbin`

The workflow has this set as its default `$PATH` so it will work on either style of Mac.

```bash
/opt/homebrew/bin:/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin:${PATH}
```

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
* `grunt {query}`: Node.js task-runner [Grunt Plugins](http://gruntjs.com)
* `gulp {query}`: Node.js task-runner [Gulp Plugins](http://gulpjs.com)
* `hackage {query}`: Haskell [package archive](https://hackage.haskell.org)
* `hex {query}`: Elixir [Hex Packages](http://hex.pm)
* `maven {query}`: Java [Maven Libraries](http://mvnrepository.com)
* `metacran {query}`: [R Packages](https://www.r-pkg.org/)
* `npm {query}`: Node.js [NPM Packages](https://www.npmjs.org)
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

All workflows require an internet connection.

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

![Alcatraz sample results](./screenshots/alcatraz.png "Sample alcatraz result")
![Bower sample results](./screenshots/bower.png "Sample bower result")
![Chef sample results](./screenshots/brew.png "Sample brew result")
![CocoaPods sample results](./screenshots/chef.png "Sample chef result")
![Composer sample results](./screenshots/cocoa.png "Sample cocoa result")
![Docker sample results](./screenshots/composer.png "Sample composer result")
![Grunt sample results](./screenshots/docker.png "Sample docker result")
![Gulp sample results](./screenshots/gems.png "Sample gems result")
![Hex sample results](./screenshots/grunt.png "Sample grunt result")
![Homebrew sample results](./screenshots/gulp.png "Sample gulp result")
![Maven sample results](./screenshots/hex.png "Sample hex resuls")
![Metacran sample results](./screenshots/maven.png "Sample maven result")
![NPM sample results](./screenshots/metacran.png "Sample metacran.png result")
![PEAR sample results](./screenshots/npm.png "Sample npm result")
![PyPi sample results](./screenshots/pear.png "Sample pear result")
![RPMfind sample results](./screenshots/pypi.png "Sample pypi result")
![Rubygems sample results](./screenshots/rpm.png "Sample rpm result")
![Sublime Text sample results](./screenshots/stpm.png "Sample stpm result")
![Yarn sample results](./screenshots/yarn.png "Sample yarn result")
![Yo sample results](./screenshots/yo.png "Sample yo result")
