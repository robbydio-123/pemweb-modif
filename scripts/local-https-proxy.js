const fs = require("node:fs");
const http = require("node:http");
const https = require("node:https");

const certificatePath = process.env.LOCAL_HTTPS_PFX;
if (!certificatePath) {
    throw new Error("LOCAL_HTTPS_PFX is not set.");
}

const httpPort = Number(process.env.LOCAL_HTTPS_HTTP_PORT || 8000);
const httpsPort = Number(process.env.LOCAL_HTTPS_PORT || 8080);
const startPath = process.env.LOCAL_HTTPS_START_PATH || "/index.php";
if (!Number.isInteger(httpPort) || !Number.isInteger(httpsPort)) {
    throw new Error("HTTP and HTTPS ports must be valid integers.");
}

const server = https.createServer(
    {
        pfx: fs.readFileSync(certificatePath),
        passphrase: process.env.LOCAL_HTTPS_PASSPHRASE,
    },
    (request, response) => {
        const upstream = http.request(
            {
                hostname: "127.0.0.1",
                port: httpPort,
                path: request.url,
                method: request.method,
                headers: {
                    ...request.headers,
                    host: `127.0.0.1:${httpPort}`,
                    "x-forwarded-host": `localhost:${httpsPort}`,
                    "x-forwarded-proto": "https",
                },
            },
            (upstreamResponse) => {
                response.writeHead(
                    upstreamResponse.statusCode ?? 502,
                    upstreamResponse.headers,
                );
                upstreamResponse.pipe(response);
            },
        );

        upstream.on("error", (error) => {
            console.error(`PHP server request failed: ${error.message}`);
            if (!response.headersSent) {
                response.writeHead(502, { "content-type": "text/plain; charset=utf-8" });
            }
            response.end("PHP server is unavailable.");
        });

        request.pipe(upstream);
    },
);

server.on("error", (error) => {
    console.error(`HTTPS server failed: ${error.message}`);
    process.exitCode = 1;
});

server.listen(httpsPort, "127.0.0.1", () => {
    console.log(`Rental Motor HTTPS: https://localhost:${httpsPort}${startPath}`);
    console.log("Keep this task running while using the local site.");
});
