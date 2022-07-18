//function that opens two files and copies the contents of one file to the other
//dst= destination مقصد , src= source مبدأ

package main

import (
	"io"
	"os"
)

func CopyFile(dstName, srcName string) (written int64, err error) {
	src, err := os.Open(srcName)
	if err != nil {
		return
	}

	dst, err := os.Create(dstName)
	if err != nil {
		return
	}

	written, err = io.Copy(dst, src)
	dst.Close()
	src.Close()
	return
}

func main() {
	CopyFile("pasteintothisfile", "copyfromthisfile")
}
