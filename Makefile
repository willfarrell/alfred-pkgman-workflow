.PHONY: dist clean updateInfoPlist

distDir=./dist


dist: | @composer @copy
clean:
	rm -rf $(distDir)/*


@composer:
	rm -rf vendor
	composer install -v -o --no-dev

@copy:
	$(shell [ ! -d "$(distDir)" ] && mkdir -p "$(distDir)")
	cp -frv bin dist/
	cp -frv src dist/
	cp -fv composer.* dist/
	cp -frv icon-cache dist/
	cp -frv vendor dist/

updateInfoPlist:
	[ "${ALFRED_PKGMAN_WORKFLOW_DIR}" == "" ] && echo 'ALFRED_PKGMAN_WORKFLOW_DIR is not set! Aborting!' \
	|| cp -fv "${ALFRED_PKGMAN_WORKFLOW_DIR}"/info.plist .