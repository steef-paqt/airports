SHELL := /bin/sh
.DEFAULT_GOAL = welcome

welcome: banner help

msg_fix = @echo "\033[0;43;30m🔨 `printf '%-52s' $(1)` \033[0m"
msg_validate = @echo "\033[106;30m🔍 `printf '%-52s' $(1)` \033[0m"
msg_docker = echo "\033[0;107;30m🐳 `printf '%-52s' $(1)` \033[0m"

banner:
	@clear
	@echo "$$(tput bold)$$(tput setaf 2)"
	@echo "$(APP_NAME)"
	@echo "$$(tput sgr0)"

# See https://gist.github.com/klmr/575726c7e05d8780505a#gistcomment-2858004
## [💡] Show this help
help:
	@echo "$$(tput bold)Available commands:$$(tput sgr0)";sed -ne"/^## /{h;s/.*//;:d" -e"H;n;s/^## //;td" -e"s/:.*//;G;s/\\n## /---/;s/\\n/ /g;p;}" ${MAKEFILE_LIST}|LC_ALL='C' sort -f|awk -F --- -v n=$$(tput cols) -v i=29 -v a="$$(tput setaf 6)" -v z="$$(tput sgr0)" '{printf"%s%*s%s ",a,-i,$$1,z;m=split($$2,w," ");l=n-i;for(j=1;j<=m;j++){l-=length(w[j])+1;if(l<= 0){l=n-i-length(w[j])-1;printf"\n%*s ",-i," ";}printf"%s ",w[j];}printf"\n";}'

# List all available make commands
list:
	@LC_ALL=C $(MAKE) -pRrq -f $(MAKEFILE_LIST) -f ./makefile : 2>/dev/null | awk -v RS= -F: '/^# {if ($$1 !~ "^[#.]") {print $$1}}' | sort | egrep -v -e '^[^[:alnum:]]' -e '^$@$$'
