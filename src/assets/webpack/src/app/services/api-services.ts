import { HttpClientConfiguration, IHttpClient } from '@aurelia/fetch-client';
import {ILogger, resolve} from 'aurelia';
import {ConfigService} from "./config-service";

export class ApiServices {

    public constructor(
        private readonly httpClient: IHttpClient = resolve(IHttpClient),
        private readonly configService:ConfigService = resolve(ConfigService),
        private readonly logger: ILogger = resolve(ILogger).scopeTo('ApiServices')
    )
    {
        this.logger.trace('constructor');
        this.httpClient.configure((config: HttpClientConfiguration) => {
            config.withDefaults({
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'include'
            }).withInterceptor({
                request(request) {
                    console.log(`Requesting ${request.method} ${request.url}`);
                    return request;
                },
                response(response) {
                    console.log(`Received ${response.status} ${response.url}`);
                    return response;
                },
            }).rejectErrorResponses();
            return config;
        });

    }

    public delete(url:string): Promise<string>
    {
        return this.httpClient.fetch(url, {
            method: 'DELETE',
        })
            .then((response:Response) => {
                return response.text();
            });
    }
}
