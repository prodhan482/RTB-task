<?php

function parseJson($jsonString)
{
    $data = json_decode($jsonString, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON Parsing Error: " . json_last_error_msg());
        error_log("Offending JSON: " . $jsonString);
        throw new Exception("Invalid JSON format: " . json_last_error_msg());
    }
    return $data;
}

function selectCampaign($bidRequest, $campaign)
{
    $imp = $bidRequest['imp'][0];
    $device = $bidRequest['device'];
    $geo = $device['geo'];

    error_log("Geo Country: " . $geo['country']);
    error_log("Campaign Country: " . $campaign['country']);
    error_log("Device OS: " . $device['os']);
    error_log("Campaign OS: " . $campaign['hs_os']);
    error_log("Bid Floor: " . $imp['bidfloor']);
    error_log("Campaign Price: " . $campaign['price']);

    $matches = $campaign['country'] === $geo['country']
        && $campaign['price'] >= $imp['bidfloor']
        && strpos($campaign['hs_os'], strtoupper($device['os'])) !== false;

    return $matches ? $campaign : null;
}

function generateBidResponse($bidRequest, $campaign)
{
    return [
        "id" => $bidRequest['id'],
        "bidid" => uniqid(),
        "seatbid" => [
            [
                "bid" => [
                    [
                        "price" => $campaign['price'],
                        "adm" => json_encode([
                            "native" => [
                                "assets" => [
                                    [
                                        "id" => 101,
                                        "title" => ["text" => $campaign['native_title']],
                                        "required" => 1
                                    ],
                                    [
                                        "id" => 104,
                                        "img" => [
                                            "url" => $campaign['image_url'],
                                            "w" => 600,
                                            "h" => 600
                                        ],
                                        "required" => 1
                                    ],
                                    [
                                        "id" => 102,
                                        "data" => ["value" => $campaign['native_data_value'], "type" => 2],
                                        "required" => 1
                                    ],
                                    [
                                        "id" => 103,
                                        "data" => ["value" => $campaign['native_data_cta'], "type" => 12],
                                        "required" => 1
                                    ]
                                ],
                                "link" => [
                                    "url" => $campaign['url'],
                                ],
                                "ver" => "1.2"
                            ]
                        ]),
                        "id" => uniqid(),
                        "impid" => $bidRequest['imp'][0]['id'],
                        "crid" => $campaign['creative_id'],
                        "bundle" => $campaign['portalname']
                    ]
                ],
                "seat" => "1003",
                "group" => 0
            ]
        ]
    ];
}

try {
    $bidRequestJson = file_get_contents("php://input");
    if (!$bidRequestJson) {
        throw new Exception("Empty input received.");
    }

    $bidRequest = parseJson($bidRequestJson);

    $campaignJson = json_encode([
        "country" => "BGD",
        "price" => 0.1,
        "hs_os" => "ANDROID",
        "native_title" => "GameStar",
        "native_data_value" => "Play Tournament Game",
        "native_data_cta" => "PLAY",
        "image_url" => "https://example.com/image.jpg",
        "url" => "https://gamestar.shabox.mobi/",
        "creative_id" => 168962,
        "portalname" => "com.imo.android.imoim"
    ]);

    $campaign = parseJson($campaignJson);

    $selectedCampaign = selectCampaign($bidRequest, $campaign);

    if (!$selectedCampaign) {
        throw new Exception("No suitable campaign found for the bid request.");
    }

    $bidResponse = generateBidResponse($bidRequest, $selectedCampaign);
    header('Content-Type: application/json');
    echo json_encode($bidResponse, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => $e->getMessage()]);
}
