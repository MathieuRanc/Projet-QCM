PROG = omr1
OUTPUT_DIR=../../bin/

#LDFLAGS=-O2 `Magick++-config --cppflags --cxxflags --ldflags --libs`
LDFLAGS=-O2 `/usr/lib/x86_64-linux-gnu/ImageMagick-6.9.7/bin-q16/Magick++-config --cppflags --cxxflags --ldflags --libs`
VPATH=./:${OUTPUT_DIR}

all: ${PROG}

omr1: omr.cpp
	${CXX} ${LDFLAGS} $< ${LDFLAGS} -o ${OUTPUT_DIR}/$@

clean:
	rm -fv ${OUTPUT_DIR}/${PROG} *.o
