export function convertValues(payload) {
    let string = '{'
    const keys = Object.keys(payload)
    const values = Object.values(payload)
    values.forEach((value, index) => {
        if (value && typeof value === 'object') {
            convertValues(value)
        } else {
            const newValue = value ? `\u0022${value}\u0022` : value
            string += `${keys[index]}: ${newValue},`
        }
    })
    string += '}'
    return string;
}

export const isValidVimeoUrl = (url) => {
    return url.match(/(vimeo\.com)(.+)?$/);
};

export const isValidYoutubeUrl = (url) => {
    return url.match(/(youtube\.com|youtu\.be)(.+)?$/);
};

export const getVimeoEmbedUrl = (options) => {
    if (!isValidVimeoUrl(options.src)) {
        return null;
    }

    // if is already an embed url, return it
    if (options.src.includes("/video/")) {
        return options.src;
    }

    const videoIdRegex = /\.com\/([0-9]+)/gm;
    const matches = videoIdRegex.exec(options.src);

    if (!matches || !matches[1]) {
        return null;
    }

    let outputUrl = `https://player.vimeo.com/video/${matches[1]}`;

    let params = [];

    if (Object.values(options.options).length > 0) {
        for (const [key, value] of Object.entries(options.options)) {
            params.push(`${key}=${value}`);
        }

        outputUrl += `?${params.join("&")}`;
    }

    return outputUrl;
};

export const getYouTubeEmbedUrl = (options) => {
    if (! isValidYoutubeUrl(options.src)) {
        return null;
    }

    const embedUrl = options.options.nocookie
        ? "https://www.youtube-nocookie.com/embed/"
        : "https://www.youtube.com/embed/";

    delete options.options.nocookie

    let id = null;

    // Already an embed URL
    if (options.src.includes("/embed/")) {
        return options.src;
    }

    // Extract the video ID for youtu.be URLs
    if (options.src.includes("youtu.be")) {
        id = options.src.split("/").pop();
    }

    // Extract the video ID for /shorts/ URLs
    if (options.src.includes("/shorts/")) {
        id = options.src.split("/shorts/").pop();
    }

    // Extract the video ID for standard YouTube URLs (?v=)
    if (!id) {
        const videoIdRegex = /v=([-\w]+)/gm;
        const matches = videoIdRegex.exec(options.src);
        if (matches && matches[1]) {
            id = matches[1];
        }
    }

    if (!id) {
        return null;
    }

    let outputUrl = `${embedUrl}${id}`;
    let params = [];

    if (Object.values(options.options).length > 0) {
        for (const [key, value] of Object.entries(options.options)) {
            params.push(`${key}=${value}`);
        }

        outputUrl += `?${params.join("&")}`;
    }

    return outputUrl;
};
