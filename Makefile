.PHONY: dist clean copyIcons buildWorkflow release linkSourceFoldersToWorkflow

distDir=./dist
workflowName="Package Managers.alfredworkflow"

dist: | composer copySources copyIcons buildWorkflow
clean:
	rm -rf $(distDir)/*


composer:
	rm -rf vendor
	composer install -v -o --no-dev

copySources:
	$(shell [ ! -d "$(distDir)" ] && mkdir -p "$(distDir)")
	cp -frv bin $(distDir)
	cp -frv src $(distDir)
	cp -fv composer.* $(distDir)
	cp -frv icon-cache $(distDir)
	cp -frv vendor $(distDir)

copyIcons:
	-[[ "${ALFRED_PKGMAN_WORKFLOW_DIR}" == "" ]] && { echo "ALFRED_PKGMAN_WORKFLOW_DIR is not set! Aborting!"; exit 1; }
	find "${ALFRED_PKGMAN_WORKFLOW_DIR}" -type f -name "*.png" -depth 1 -exec cp -fv {} $(distDir) \;

buildWorkflow:
	-[[ "${ALFRED_PKGMAN_WORKFLOW_DIR}" == "" ]] && { echo "ALFRED_PKGMAN_WORKFLOW_DIR is not set! Aborting!"; exit 1; }
	cp -fv "${ALFRED_PKGMAN_WORKFLOW_DIR}"/info.plist $(distDir)
	cd $(distDir) && zip -r $(workflowName) * -x "*.DS_Store"

release: | dist
	cp -fv $(distDir)/info.plist .
	cp -fv $(distDir)/$(workflowName) .


linkSourceFoldersToWorkflow:
	-[[ "${ALFRED_PKGMAN_WORKFLOW_DIR}" == "" ]] && { echo "ALFRED_PKGMAN_WORKFLOW_DIR is not set! Aborting!"; exit 1; }

	-[ -d "${ALFRED_PKGMAN_WORKFLOW_DIR}/bin" ] && mv -v "${ALFRED_PKGMAN_WORKFLOW_DIR}/bin" "${ALFRED_PKGMAN_WORKFLOW_DIR}/bin.bak"
	ln -vsfF $(PWD)/bin "${ALFRED_PKGMAN_WORKFLOW_DIR}"

	-[ -d "${ALFRED_PKGMAN_WORKFLOW_DIR}/src" ] && mv -v "${ALFRED_PKGMAN_WORKFLOW_DIR}/src" "${ALFRED_PKGMAN_WORKFLOW_DIR}/src.bak"
	ln -vsfF $(PWD)/src "${ALFRED_PKGMAN_WORKFLOW_DIR}"

	-[ -d "${ALFRED_PKGMAN_WORKFLOW_DIR}/vendor" ] && mv -v "${ALFRED_PKGMAN_WORKFLOW_DIR}/vendor" "${ALFRED_PKGMAN_WORKFLOW_DIR}/vendor.bak"
	ln -vsfF $(PWD)/vendor "${ALFRED_PKGMAN_WORKFLOW_DIR}"
