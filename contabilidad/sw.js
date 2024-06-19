let CACHE_INMUTABLE_NAME = 'inmutableContaRexy-v1'

self.addEventListener('install', e => {
    
    let cache_inmutable = caches.open(CACHE_INMUTABLE_NAME).then(appCache => {
        return appCache.addAll([
            'https://code.jquery.com/jquery-3.5.1.min.js',
            'https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js',
            'https://kit.fontawesome.com/0265b153d4.js',
            'https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css',
            'https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js',
            'https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css',
            'https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js',
            'https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js'
        ])
    })

    e.waitUntil(Promise.all([cache_inmutable]))
    self.skipWaiting()
})

self.addEventListener('fetch', e => {

    /* 
        2 - cache with Network Fallback
        primero se fija en la cache sino va a internet
    */
    const respuesta = caches.match(e.request)
    .then( res => {
        if(res) return res

        /* no existe el archivo y va a la web */
        return fetch( e.request ).then(newResp => {
            return newResp.clone()
        })
    })
    e.respondWith( respuesta )

})