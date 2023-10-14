const TAGS = ["<h1>", "<h2>", "<h3>", "<h4>", "<h5>", "<h6>"];
function closeTags(element) {
    let tags = [];
    for (let i = 0; i < element.length; i++) {
        if (element[i] !== "<")
            continue;
        if (i + 1 < element.length && element[i + 1] === "/") {
            for (const tag of TAGS) {
                const closed = "</" + tag.substring(2);
                if (element.substr(i, closed.length) !== closed)
                    continue;
                tags.pop();
                i += tag.length - 2;
                break;
            }
        }

        for (const tag of TAGS) {
            if (element.substring(i, tag.length) !== tag)
                continue;
            tags.push(tag);
            i += tag.length - 2;
            break;
        }
    }

    for (const tag of tags)
        element += "</" + tag.substring(1);
    return element;
}

function parseElement(element) {
    let isParagraph = true
    for (const tag of TAGS)
        if (element.substring(0, tag.length) === tag) {
            isParagraph = false;
            break;
        }
    if (isParagraph)
        element = `<p>${element}</p>`;
    return closeTags(element);
}

function parseMarkdown(markdown) {
    let elements = [];
    let element = "";
    markdown.split('\n').forEach(line => {
        if (line === "") {
            if (element)
                elements.push(parseElement(element));
            element = "";
            return;
        } else if (element)
            element += "<br>";

        let headings = 0
        while (headings < line.length && line[headings] === "#" && headings < 6)
            headings++;
        if (markdown[headings] === " ") {
            element += `<h${headings}>`;
            line = line.substr(headings + 1);
        }

        element += line;
    });

    if (element)
        elements.push(parseElement(element));
    return elements.join("\n");
}
