const crypto = require("crypto");

const {data: {requestsLog: requests}} = require("./logs.json");

const CHARS = `0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"#$%&\\'()*+,-./:;<=>?@[\\\\]^_\`{|}~ \\t\\n\\r`.split("");

const hashes = requests
    .filter(({name}) => name == "confession")
    .map(({args}) => JSON.parse(args).hash);

const decryptNextChar = (prefix, hash) => CHARS.find(
    c => crypto
        .createHash("sha256")
        .update(prefix + c)
        .digest("hex") == hash
);

const decrypt = hashes => hashes.reduce((prefix, hash, i, arr) => {
    const c = decryptNextChar(prefix, hash);

    if (!c)
        throw Error("Hash sequence doesn't add up");

    console.info(`Decrypted sequence: ${(prefix + c).padEnd(arr.length, "*")}`);

    return prefix + c;
}, "");

console.info("\nThe flag is:", decrypt(hashes));
