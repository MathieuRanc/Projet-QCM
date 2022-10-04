PROG=omr2
OUTPUT_DIR=../../bin/

CC=gcc
CFLAGS=-O2 -Wall `sdl-config --cflags`
LDFLAGS=`sdl-config --libs` -lm -lSDL -lSDL_image -lSDL_gfx

all: install

install: ${PROG}
	cp ${PROG} ${OUTPUT_DIR}

${PROG}: omr2.o filtre.o
#	gcc ${LDFLAGS} -o ${PROG} omr2.o filtre.o
	gcc omr2.o filtre.o -o omr2 -lm -lSDL -lSDL_image -lSDL_gfx

clean:
	rm -rf *.o *~ ${PROG} ${OUTPUT_DIR}/${PROG}
