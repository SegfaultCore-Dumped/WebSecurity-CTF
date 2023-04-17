package main

import (
	"os"
	"bufio"
	"fmt"
	"net/http"
)

func main() {
    reader := bufio.NewReader(os.Stdin)
    s, _ := reader.ReadString(0)
    url := "http://" + s + ":80/"

	client := &http.Client{}
	request, err := http.NewRequest("GET", url, nil)

	if err != nil {
		fmt.Printf("request error: %s\n", err)
		return
	}

	resp, err := client.Do(request)

	if err != nil {
		fmt.Printf("response error: %s\n", err)
		return
	}

	resp.Body.Close()
}
