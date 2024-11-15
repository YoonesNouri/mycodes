package main

import (
  "context"
  "log"
  "os"

  "github.com/chromedp/chromedp"
)

func main() {
  // create context
  ctx, cancel := chromedp.NewContext(
    context.Background(),
    // chromedp.WithDebugf(log.Printf),
  )
  defer cancel()

  // capture screenshot of an element
  var buf []byte
  // capture entire browser viewport, returning png with quality=90
  if err := chromedp.Run(ctx, fullScreenshot(`https://erp.sjau.ac.ir/Hermes`, 90, &buf)); err != nil {
    log.Fatal(err)
  }
  if err := os.WriteFile("fullScreenshot.png", buf, 0o644); err != nil {
    log.Fatal(err)
  }

  log.Printf("wrote elementScreenshot.png and fullScreenshot.png")
}

// fullScreenshot takes a screenshot of the entire browser viewport.
//
// Note: chromedp.FullScreenshot overrides the device's emulation settings. Use
// device.Reset to reset the emulation and viewport settings.
func fullScreenshot(urlstr string, quality int, res *[]byte) chromedp.Tasks {
  return chromedp.Tasks{
    chromedp.Navigate(urlstr),
    chromedp.FullScreenshot(res, quality),
  }
}