help:
	@echo "Please use \`make <target>' where <target> is one of"
	@echo "  test           to perform unit tests"
	@echo "  coverage       to perform unit tests and create a code coverage report in artifacts/coverage"
	@echo "  clean          to remove the artifacts in artifacts/"

coverage:
	vendor/bin/phpunit --coverage-html=artifacts/coverage

clean:
	rm -rf artifacts/*

test:
	vendor/bin/phpunit