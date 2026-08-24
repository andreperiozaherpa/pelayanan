export namespace main {
	
	export class AppConfig {
	    api_base_url: string;
	    ws_url: string;
	    mock_api: boolean;
	    gerai_id?: number;
	    loket_id?: string;
	
	    static createFrom(source: any = {}) {
	        return new AppConfig(source);
	    }
	
	    constructor(source: any = {}) {
	        if ('string' === typeof source) source = JSON.parse(source);
	        this.api_base_url = source["api_base_url"];
	        this.ws_url = source["ws_url"];
	        this.mock_api = source["mock_api"];
	        this.gerai_id = source["gerai_id"];
	        this.loket_id = source["loket_id"];
	    }
	}
	export class CallPayload {
	    queue_number: string;
	    gerai_name: string;
	    agency: string;
	    timestamp: number;
	    voice: string;
	    rate: number;
	    pitch: number;
	    chime_sound: string;
	
	    static createFrom(source: any = {}) {
	        return new CallPayload(source);
	    }
	
	    constructor(source: any = {}) {
	        if ('string' === typeof source) source = JSON.parse(source);
	        this.queue_number = source["queue_number"];
	        this.gerai_name = source["gerai_name"];
	        this.agency = source["agency"];
	        this.timestamp = source["timestamp"];
	        this.voice = source["voice"];
	        this.rate = source["rate"];
	        this.pitch = source["pitch"];
	        this.chime_sound = source["chime_sound"];
	    }
	}

}

