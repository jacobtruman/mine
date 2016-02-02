function parse_git_branch {
  ref=$(git symbolic-ref HEAD 2> /dev/null) || return
  echo "("${ref#refs/heads/}") "
}

GRAY="\[\e[0;30m\]"
RED="\[\e[0;31m\]"
GREEN="\[\e[0;32m\]"
YELLOW="\[\e[0;33m\]"
BLUE="\[\e[0;34m\]"
MAGENTA="\[\e[0;35m\]"
CYAN="\[\e[0;36m\]"
WHITE="\[\e[0;37m\]"

BOLD_BLACK="\[\e[1;30m\]"
BOLD_RED="\[\e[1;31m\]"
BOLD_GREEN="\[\e[1;32m\]"
BOLD_YELLOW="\[\e[1;33m\]"
BOLD_BLUE="\[\e[1;34m\]"
BOLD_MAGENTA="\[\e[1;35m\]"
BOLD_CYAN="\[\e[1;36m\]"
BOLD_WHITE="\[\e[1;37m\]"

PS1="\342\224\214$WHITE[ $BLUE\u@\h$WHITE - $RED\t$WHITE - $GREEN\d $MAGENTA\$(parse_git_branch)$WHITE]\n\342\224\224\342\206\222 $YELLOW\w $WHITE> "

export PS1
export MYSQL_PS1="[\h \d] mysql> "
export CLICOLOR=1
export LSCOLORS=gxBxhxDxfxhxhxhxhxcxcx

alias ll='ls -la'

alias vlc='/Applications/VLC.app/Contents/MacOS/VLC'
alias phpunit='php /usr/local/lib/phpunit.phar'

###MINE
alias trucraft="ssh trucraft"
alias wheatley="ssh wheatley"

###VMS
alias vm679="ssh vm679"

###DCS
alias esdal="ssh essjo"
alias essjo="ssh esdal"
alias esark="ssh esark"

###PROD
alias sjo="ssh sjo"
alias dal="ssh dal"
alias lon="ssh lon"
alias sin="ssh sin"
alias pnw="ssh pnw"

###DMIG SERVERS
alias dmig3="ssh dmig3"
alias dmig4="ssh dmig4"
alias qe6="ssh qe6"
alias qe10="ssh qe10"

export PATH="/usr/local/sbin:$PATH"
export PATH="/usr/local/bin:$PATH"
export PATH="/Users/jacobtruman/Library/Android/sdk/platform-tools:$PATH"

export JAVA_HOME="/Library/Java/JavaVirtualMachines/jdk1.7.0_17.jdk/Contents/Home"

if [ -f /usr/local/bin/screenfetch ]; then screenfetch; fi

# source additional bash profile scripts in the .bash.d directory
if [ -d $HOME/.bash.d ]; then
    for i in $HOME/.bash.d/.*.sh; do
        if [ -r $i ]; then
            . $i
        fi
    done
    unset i
fi
