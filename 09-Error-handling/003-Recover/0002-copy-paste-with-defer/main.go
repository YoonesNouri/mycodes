//function that opens two files and copies the contents of one file to the other
//dst= destination مقصد , src= source مبدأ
//with defer

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
	defer src.Close()

	dst, err := os.Create(dstName)
	if err != nil {
		return
	}
	defer dst.Close()

	return io.Copy(dst, src)
}

func main() {
	CopyFile("pastebydefer", "copybydefer")
}
