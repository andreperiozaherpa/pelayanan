package main

import (
	"bufio"
	"log"
	"os"
	"strings"
)

type AppConfig struct {
	APIBaseURL string `json:"api_base_url"`
	WSURL      string `json:"ws_url"`
	MockAPI    bool   `json:"mock_api"`
	GeraiID    int    `json:"gerai_id,omitempty"`
	LoketID    string `json:"loket_id,omitempty"`
}

func DefaultConfig() AppConfig {
	return AppConfig{
		APIBaseURL: "http://localhost:8080/api/v1",
		WSURL:      "ws://localhost:8080/ws",
	}
}

func LoadConfig() AppConfig {
	envPath := ".env"
	if _, err := os.Stat(envPath); err == nil {
		if err := loadEnvFile(envPath); err != nil {
			log.Printf("WARNING: failed to parse .env file: %v", err)
		}
	}

	cfg := DefaultConfig()

	if v := os.Getenv("API_BASE_URL"); v != "" {
		cfg.APIBaseURL = v
	}
	if v := os.Getenv("WS_URL"); v != "" {
		cfg.WSURL = v
	}
	if v := os.Getenv("MOCK_API"); v == "true" {
		cfg.MockAPI = true
	}

	return cfg
}

func loadEnvFile(path string) error {
	f, err := os.Open(path)
	if err != nil {
		return err
	}
	defer f.Close()

	scanner := bufio.NewScanner(f)
	for scanner.Scan() {
		line := strings.TrimSpace(scanner.Text())
		if line == "" || strings.HasPrefix(line, "#") {
			continue
		}

		parts := strings.SplitN(line, "=", 2)
		if len(parts) != 2 {
			continue
		}

		key := strings.TrimSpace(parts[0])
		val := strings.TrimSpace(parts[1])

		if key == "" {
			continue
		}

		val = strings.Trim(val, "'\"")
		os.Setenv(key, val)
	}

	return scanner.Err()
}
