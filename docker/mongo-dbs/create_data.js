
function lorem(){

        var words = ["a", "ac", "accumsan", "adipiscing", "aenean", "aliqua", "aliquam", "aliquet", "amet", "ante",
        "arcu", "at", "auctor", "augue", "bibendum", "blandit", "commodo", "condimentum", "congue", "consectetur",
        "consequat", "convallis", "cras", "cum", "curabitur", "cursus", "dapibus", "diam", "dictum", "dictumst",
        "dignissim", "dis", "dolor", "dolore", "do", "donec", "dui", "duis", "egestas", "eget", "eiusmod", "eleifend",
        "elementum", "elit", "enim", "erat", "eros", "est", "et", "etiam", "eu", "euismod", "facilisi", "facilisis",
        "fames", "faucibus", "felis", "fermentum", "feugiat", "fringilla", "fusce", "gravida", "habitant", "habitasse",
        "hac", "hendrerit", "iaculis", "id", "imperdiet", "in", "incididunt", "integer", "interdum", "ipsum", "justo",
        "labore", "lacinia", "lacus", "laoreet", "lectus", "leo", "libero", "ligula", "lobortis", "lorem", "luctus",
        "maecenas", "magna", "magnis", "malesuada", "massa", "mattis", "mauris", "metus", "mi", "molestie", "mollis",
        "montes", "morbi", "mus", "nam", "nascetur", "natoque", "nec", "neque", "netus", "nibh", "nisi", "nisl", "non",
        "nulla", "nullam", "nunc", "odio", "orci", "ornare", "parturient", "pellentesque", "penatibus", "pharetra",
        "phasellus", "placerat", "platea", "porta", "porttitor", "posuere", "potenti", "praesent", "pretium", "proin",
        "pulvinar", "purus", "quam", "quis", "quisque", "rhoncus", "ridiculus", "risus", "rutrum", "sagittis", "sapien",
        "scelerisque", "sed", "sem", "semper", "senectus", "sit", "sociis", "sodales", "sollicitudin", "suscipit",
        "suspendisse", "tellus", "tempor", "tempus", "tincidunt", "tortor", "tristique", "turpis", "ullamcorper",
        "ultrices", "ultricies", "urna", "ut", "varius", "vehicula", "vel", "velit", "venenatis", "vestibulum", "vitae",
        "vivamus", "viverra", "volutpat", "vulputate"];

        return words[Math.floor(Math.random()*words.length)] + ' ' +
            words[Math.floor(Math.random()*words.length)] + ' ' +
            words[Math.floor(Math.random()*words.length)] + ' ' +
            words[Math.floor(Math.random()*words.length)] + ' ' +
            words[Math.floor(Math.random()*words.length)] + ' ' +
            words[Math.floor(Math.random()*words.length)] + ' ' +
            words[Math.floor(Math.random()*words.length)] + ' ' +
            words[Math.floor(Math.random()*words.length)] + ' ' +
            words[Math.floor(Math.random()*words.length)] + ' ' +
            words[Math.floor(Math.random()*words.length)];
};


for (var i = 0; i <= 2000000; i++) {
    db.speed.insert({
        a: i,
        b: new Date(),
        c: 'c' + i,
        d: [i*3, Math.log(i), Math.cos(i), Math.sqrt(i)],
        e: Math.random().toString(36).substring(2),
        f:lorem(),
        g:{
            h:i*2,
            e: Math.random().toString(36).substring(7),
            f:lorem()
        }
    });
    // i +=1; // for a smaller dataset
}