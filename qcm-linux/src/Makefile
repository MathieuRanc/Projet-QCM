MAKE_DIRECTORIES=omr1 omr2 omr3
ANT_DIRECTORIES=mail omr_rotate
SHELL=/bin/bash

all:
	@for DIRECTORY in ${MAKE_DIRECTORIES}; do \
		make -C $${DIRECTORY} all; \
	done
	@for DIRECTORY in ${ANT_DIRECTORIES}; do \
		# cd $${DIRECTORY}; \
		pushd $${DIRECTORY}; \
		ant all; \
		popd;\
		# cd ..; \
	done;

clean:
	@for DIRECTORY in ${MAKE_DIRECTORIES}; do \
		make -C $${DIRECTORY} clean; \
	done
	@for DIRECTORY in ${ANT_DIRECTORIES}; do \
		bash -c pushd $${DIRECTORY}; \
		ant clean; \
		popd; \
	done
