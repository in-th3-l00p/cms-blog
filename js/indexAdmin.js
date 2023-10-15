let showPrivatePosts = false;

document.getElementById("private-posts-toggler").onclick = () => {
    showPrivatePosts = !showPrivatePosts;
    if (showPrivatePosts) {
        document
            .getElementById("private-posts-container")
            .style
            .setProperty("display", "block");
        document
            .getElementById("private-posts-toggler")
            .style
            .setProperty("transform", "rotateX(180deg)")
    } else {
        document
            .getElementById("private-posts-container")
            .style
            .setProperty("display", "none");
        document
            .getElementById("private-posts-toggler")
            .style
            .setProperty("transform", "rotateX(0deg)")
    }
}