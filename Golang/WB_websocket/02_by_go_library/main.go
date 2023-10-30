package main

import (
	"fmt"
"golang.org/x/net/websocket"
)

type server struct {
	conns map[*websocket.Conn]bool
}

func Newserver() *server {
	return &server{
		conns: make(map[*websocket.Conn]bool),
	}
}

func (s *Server) handleWSOrderbook(ws *websocket.Conn){
		fmt.Println("new incoming connection from client to orderbook feed", ws.RemoteAddr())

		for {
			payload := fmt.Sprintf("orderbook data -> %d\n", time.Now().UnixNano())
			ws.Write([]byte(payload))  
			time.Sleep(time.Second * 2)
		}

}

func (s *server) handleWD(ws *websocket.Conn){
	fmt.Println("new incoming connection from client", ws.RemoteAddr())
	
	s.conns[ws] = true
	
	s.readLoop(ws)
}

func (s *Server) readLoop(ws *websocket.Conn) {
	buf := make([]byte, 1024)
	for {
		n, err := ws.Read(buf)
		if err != nil {
			if err == io.EOF {
				break
			}
			fmt.println("read error", err)
			continue
		}
		msg := buf[:n]
	s.broadcast(msg)
	}
}

func (s *Server) broadcast(b []byte) {
	for ws := range s.conns{
		go func(ws *websocket.Conn) {
if _, err := ws.Write(b); err != nil {
	fmt.Println("write error:",err)
}
		}(ws) 
	}
}

func  main()  {
	server := Newserver()
	http.Handle("/ws", websocket.Handler(server.handleWD))
	http.Handle("/orderbookfeed", websocket.Handler(server.handleWSOrderbook))
	http.ListenAndServe(":3000", nil)
	
}

