Package Managers ([Download v1.42](https://raw.github.com/willfarrell/alfred-pkgman-workflow/master/Package%20Managers.alfredworkflow))
======================================

Package Repo Search

Quick package/plugin/component (repo) lookup of for your favourite package managers. Currently supports *Alcatraz* to *Yeoman Generators*. 

## Commands
`pkgman cleardb`: Clear local database cache files
`pkgman cachedb`: Update local database cache files

`alcatraz {query}`: [Cocoa Packages](http://alcatraz.io/)
`apt-get {query}`: [Linux Packages](https://apps.ubuntu.com)
`bower {query}`: [Bower Components](http://bower.io) for JavaScript
`brew {query}`: [Homebrew Plugins](http://braumeister.org)
`chef {query}`: [Chef Cookbooks](http://supermarket.getchef.com)
`cocoa {query}`: CocoaPods can be upgraded to CocoaDocs by changing `$apple_docs` to true in the script.
`composer {query}`: PHP [Composer Packages](http://getcomposer.org)
`docker {query}`: [Docker Images](http://www.docker.io)
`gems {query}`: [Ruby Gems](http://rubygems.org)
`gradle {query}`: Java [Gradle Packages](http://www.gradle.org)
`grunt {query}`: nodeJS task-runner [Grunt Plugins](http://gruntjs.com)
`gulp {query}`: nodeJS task-runner [Gulp plugins](http://gulpjs.com)
`maven {query}`: Java [Maven Libraries](http://mvnrepository.com)
`npm {query}`: nodeJS [NPM Packages](https://www.npmjs.org)
`pear {query}`: PHP [Pear Packages](http://pear.php.net)
`puppet {query}`: [Puppet Modules](https://forge.puppetlabs.com)
`pypi {query}`: [Python Packages](https://pypi.python.org)
`raspbian {query}`: [Rasberry Pi Packages](http://www.raspbian.org)
`rpm {query}`: [Linux Packages](http://rpmfind.net)
`yo {query}`: [Yeoman Generators](http://yoeman.io)

## Action Modifiers
`default`: open README page url 
`cmd`: copy name/id to frontmost app
`shift`: cope config file name/id and version to frontmost app

## Additional Notes
All workflows require constant internet connection.

Workflows can break from time to time due to changes by the provider of the repo. If you notice a workflow stops working, post it at https://github.com/willfarrell/alfred-pkgman-workflow/issues.

All repos have caching enabled to speed up common queries. These caches are refreshed after 14 days and may take longer then expected to return results during update. You can force a cache refresh by running `pkgman cachedb` to re-download the databases (applies to `alcatraz`, `grunt`, `cocoa`). Alternatively you can run `pkgman cleardb` to remove all stored cache, but this isn't recommended. Clearing your cache is recommended after an update if the package manager you use had a bug previously.

**js, css, html:** `bower {query}`

![alt text][bower]

**node.js:** `npm {query}`

![alt text][npm]

**Yeoman Generators:** `yo {query}`

![alt text][yo]

**node.js task runner:** `grunt {query}`

![alt text][grunt]

**node.js task runner Gulp:** `gulp {query}`

![alt text][gulp]

**XCode:** `alcatraz {query}`, `cocoa {query}`

CocoaPods can be upgraded to CocoaDocs by changing `$apple_docs` to true in the script.

![alt text][alcatraz]
![alt text][cocoa]

**PHP:** `composer {query}`, `pear {query}`

![alt text][composer]
![alt text][pear]

**Python:** `pypi {query}`

The Python Package Index is very slow due to a lack on API and pagaination. A min query length has been put in place to help speed this up. You can change it in the script, `$min_query_length = 3`. Perhaps someone with a python background can improve this.

![alt text][pypi]

**Ruby:** `gems {query}`

![alt text][gems]

**Java** `maven {query}`

![alt text][maven]

**Mac OS X:** `brew {query}` (aka *homebrew*)

![alt text][brew]

**Linux:** `rpm {query}`

![alt text][rpm]

**Linux containers:** `docker {query}`

![alt text][docker]

**Chef Cookbooks:** `chef {query}`

![Screenshot of a search of Chef Supermarket][chef]

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
[gulp]: ./screenshots/gulp.png "Sample gulp result"
[maven]: ./screenshots/maven.png "Sample maven result"
[npm]: ./screenshots/npm.png "Sample npm result"
[pear]: ./screenshots/pear.png "Sample pear result"
[pypi]: ./screenshots/pypi.png "Sample pypi result"
[rpm]: ./screenshots/rpm.png "Sample rpm result"
[ruby]: ./screenshots/ruby.png "Sample ruby result"
[yo]: ./screenshots/yo.png "Sample yo result"
