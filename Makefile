# Sample Makefile for Web Programming students
#
# by Darren Provine, 14 September 2002

# These lines should be the only ones you have to change for many
# projects.
DESTDIR = /home/pierosn0/public_html/awp/FaceBark/
TARGETS = homepage.html login.html post-page_1.html post-page_2.html profile.html safer_sql.php functions.php connect.php avatar-update.php avatar-upload.php bio-update-form.php create-post.php gallery.php header.php profile.php signin.php ui-create-post.php update-bio.php upload2.php user.php verify-user.php post.php registration.php user_functions.php 
SOURCES = homepage.html login.html post-page_1.html post-page_2.html profile.html safter_sql.php functions.php connect.php avatar-update.php avatar-upload.php bio-update-form.php create-post.php gallery.php header.php profile.php signin.php ui-create-post.php update-bio.php upload2.php user.php verify-user.php post.php registration.php user_functions.php
DIRS    = css

# This target is just here to be the top target in the Makefile.
# There's nothing to compile for this one.
all: $(TARGETS)

# You might want to look up mkdir(1) to see about that -p flag.
install: $(TARGETS)
	@if [ ! -d $(DESTDIR) ] ; then mkdir -p $(DESTDIR); fi
	@for f in $(TARGETS)                 ; \
	do                                     \
		/usr/bin/install -m 444 $$f -v -t $(DESTDIR) ; \
	done
	for d in $(DIRS) ; do (cd $$d ; make install ) ; done

# Note that here we don't blow away the directory, and so we
# be sure and tell the user.  The reason not to delete the
# directory is that it may have other files in it.  Checking
# for, and deleting, any such files will have to be done manually.
# (How could this be improved?)
#
# Note also that the @ sign keeps the echo lines from being echoed
# before they are run.  (That could be confusing.)  This little
# trick (and many more) can be discovered by consulting make(1S).
deinstall:
	cd $(DESTDIR) ; /bin/rm -f $(R_TARGETS) $(X_TARGETS)
	@echo "   ==>   removed file(s): $(R_TARGETS) $(X_TARGETS)"
	@echo "   ==>   left directory : $(DESTDIR)"

redo: deinstall install
