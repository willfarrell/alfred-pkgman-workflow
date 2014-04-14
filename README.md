Package Managers ([Download v1.21](https://raw.github.com/willfarrell/alfred-pkgman-workflow/master/Package%20Managers.alfredworkflow))
======================================

Package Repo Search 

Quick package/plugin/component (repo) lookup of for your favourite package managers. Currently supports `Alcatraz`, `bower`, `CocoaDocs/CocoaPods`, `Composer`, `docker`, `yoeman`, `grunt`, `gulp`, `Homebrew`, `Maven`, `npm`, `pear`, `pypi`, `gems`, and `rpm`. All workflows require constant internet connection.

All repos have caching enabled to speed up common queries. These caches are refreshed after 14 days and may take longer then expected to return results during update. You can force a cache refresh by running `pkgman cachedb` to re-download the databases (applies to `alcatraz`, `grunt`, `cocoa`). Alternatively you can run `pkgman cleardb` to remove all stored cache, but this isn't recommended.

**js, css, html:** `bower {query}`

![alt text][bower]

**node.js:** `npm {query}`

![alt text][npm]

**Yeoman Generators:** `yo {query}`

![alt text][yo]

**node.js task runner:** `grunt {query}`

![alt text][grunt]

**node.js task runner Grunt:** `gulp {query}`

![alt text][gulp]

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


[alcatraz]: ./screenshots/alcatraz.png "Sample alcatraz result"
[bower]: ./screenshots/bower.png "Sample bower result"
[brew]: ./screenshots/brew.png "Sample brew result"
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
