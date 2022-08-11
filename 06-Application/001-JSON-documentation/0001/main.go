//به آدرس https://pkg.go.dev/encoding/json@go1.18.3 میرویم و در آنجا پکیج¬های از پیش نوشته شده json موجود است.
//نکته: وقتی در پکیج مثلا *Decoder رو return میکنه
//func NewDecoder(r io.Reader) *Decoder
//یعنی  متود های زیر که تایپ ریسیورشان *Decoder است در دسترس خواهند بود.
//func (dec *Decoder) Buffered() io.Reader
//func (dec *Decoder) Decode(v any) error
//func (dec *Decoder) DisallowUnknownFields()
//func (dec *Decoder) InputOffset() int64
//func (dec *Decoder) More() bool
//func (dec *Decoder) Token() (Token, error)
//func (dec *Decoder) UseNumber()