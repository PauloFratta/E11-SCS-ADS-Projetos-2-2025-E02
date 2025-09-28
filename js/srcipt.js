document.addEventListener('DOMContentLoaded', () => {

    const track = document.querySelector('.carousel-track');
    if (track) {
        let items = Array.from(track.children);
        function renderItems() {
            track.innerHTML = '';
            items.forEach(item => track.appendChild(item));
        }
        function rotateNext() {
            const first = items.shift(); 
            items.push(first);          
            renderItems();
        }
        function rotatePrev() {
            const last = items.pop();   
            items.unshift(last);  
            renderItems();
        }
        document.querySelector('.button.right').addEventListener('click', rotateNext);
        document.querySelector('.button.left').addEventListener('click', rotatePrev);
        setInterval(rotateNext, 10000);
    }

    const viewer = document.getElementById('viewer');
    if (viewer) {

    let scene, camera, renderer, meshes = [];
    let isDragging = false, previousMousePosition = { x: 0, y: 0 };

        function init3D() {
            scene = new THREE.Scene();
            scene.background = new THREE.Color(0x1e3a8a); 
            camera = new THREE.PerspectiveCamera(75, viewer.clientWidth / viewer.clientHeight, 0.1, 5000);
            camera.position.set(0, 0, 800);
            renderer = new THREE.WebGLRenderer({ antialias: true });
            renderer.setSize(viewer.clientWidth, viewer.clientHeight);
            viewer.innerHTML = '';
            viewer.appendChild(renderer.domElement);
            const light = new THREE.DirectionalLight(0xffffff, 1);
            light.position.set(0, 0, 1000);
            scene.add(light);

            renderer.domElement.addEventListener('mousedown', function(e) {
                isDragging = true;
                previousMousePosition = {
                    x: e.clientX,
                    y: e.clientY
                };
            });
            renderer.domElement.addEventListener('mouseup', function(e) {
                isDragging = false;
            });
            renderer.domElement.addEventListener('mouseleave', function(e) {
                isDragging = false;
            });
            renderer.domElement.addEventListener('mousemove', function(e) {
                if (!isDragging) return;
                const deltaMove = {
                    x: e.clientX - previousMousePosition.x,
                    y: e.clientY - previousMousePosition.y
                };
                previousMousePosition = {
                    x: e.clientX,
                    y: e.clientY
                };

                meshes.forEach(mesh => {
                    mesh.rotation.y += deltaMove.x * 0.01;
                    mesh.rotation.x += deltaMove.y * 0.01; 
                });
            });

            renderer.domElement.addEventListener('wheel', function(e) {
                e.preventDefault();

                const zoomSpeed = 40;
                let newZ = camera.position.z + (e.deltaY > 0 ? zoomSpeed : -zoomSpeed);

                newZ = Math.max(200, Math.min(2000, newZ));
                camera.position.z = newZ;
            }, { passive: false });
        }

        function criarCano(params) {

            meshes.forEach(m => scene.remove(m));
            meshes = [];
            const {
                diametro,
                comprimento,
                espessura,
                formato,
                segments,
                quantidade
            } = params;
            let material = new THREE.MeshPhongMaterial({ color: 0xffd600, side: THREE.DoubleSide }); // amarelo
            let materialInterno = new THREE.MeshPhongMaterial({ color: 0xffffff, side: THREE.DoubleSide, transparent: true, opacity: 0.5 });

            if (quantidade > 1) {
                let group = new THREE.Group();
                const espacamento = 6; 
                for (let i = 0; i < quantidade; i++) {
                    let mesh;
                    if (formato === 'reta') {

                        const outerRadius = diametro / 2;
                        const innerRadius = outerRadius - espessura;
                        const geoExt = new THREE.CylinderGeometry(outerRadius, outerRadius, comprimento, segments, 1, true);
                        const geoInt = new THREE.CylinderGeometry(innerRadius, innerRadius, comprimento + 2, segments, 1, true);
                        mesh = new THREE.Mesh(geoExt, material);
                        let innerMesh = new THREE.Mesh(geoInt, materialInterno);
                        mesh.add(innerMesh);
                        mesh.position.x = i * (diametro + espacamento);
                    } else if (formato === 'cotovelo') {

                        const outerRadius = diametro / 2;
                        const innerRadius = outerRadius - espessura;
                        const offset = i * (diametro + espacamento);
                        const curve = new THREE.QuadraticBezierCurve3(
                            new THREE.Vector3(offset, 0, 0),
                            new THREE.Vector3(offset + comprimento/2, 0, 0),
                            new THREE.Vector3(offset + comprimento/2, comprimento/2, 0)
                        );
                        const geoExt = new THREE.TubeGeometry(curve, segments, outerRadius, 16, false);
                        const geoInt = new THREE.TubeGeometry(curve, segments, innerRadius, 16, false);
                        mesh = new THREE.Mesh(geoExt, material);
                        let innerMesh = new THREE.Mesh(geoInt, materialInterno);
                        mesh.add(innerMesh);
                    } else if (formato === 't') {

                        const outerRadius = diametro / 2;
                        const innerRadius = outerRadius - espessura;
                        const geo1Ext = new THREE.CylinderGeometry(outerRadius, outerRadius, comprimento, segments, 1, true);
                        const geo1Int = new THREE.CylinderGeometry(innerRadius, innerRadius, comprimento + 2, segments, 1, true);
                        const geo2Ext = new THREE.CylinderGeometry(outerRadius, outerRadius, comprimento/2, segments, 1, true);
                        const geo2Int = new THREE.CylinderGeometry(innerRadius, innerRadius, comprimento/2 + 2, segments, 1, true);
                        let mesh1 = new THREE.Mesh(geo1Ext, material);
                        let mesh1Int = new THREE.Mesh(geo1Int, materialInterno);
                        mesh1.add(mesh1Int);
                        let mesh2 = new THREE.Mesh(geo2Ext, material);
                        let mesh2Int = new THREE.Mesh(geo2Int, materialInterno);
                        mesh2.add(mesh2Int);
                        mesh2.rotation.z = Math.PI/2;
                        mesh2.position.y = 0;
                        mesh2.position.x = 0;
                        mesh = new THREE.Group();
                        mesh.add(mesh1);
                        mesh2.position.z = 0;
                        mesh.add(mesh2);
                        mesh.position.x = i * (diametro + espacamento);
                    } else {

                        const outerRadius = diametro / 2;
                        const innerRadius = outerRadius - espessura;
                        const geoExt = new THREE.CylinderGeometry(outerRadius, outerRadius, comprimento, segments, 1, true);
                        const geoInt = new THREE.CylinderGeometry(innerRadius, innerRadius, comprimento + 2, segments, 1, true);
                        mesh = new THREE.Mesh(geoExt, material);
                        let innerMesh = new THREE.Mesh(geoInt, materialInterno);
                        mesh.add(innerMesh);
                        mesh.position.x = i * (diametro + espacamento);
                    }
                    group.add(mesh);
                }

                group.position.x = -((quantidade-1)*(diametro + espacamento))/2;
                scene.add(group);
                meshes.push(group);
            } else {
                let mesh;
                if (formato === 'reta') {

                    const outerRadius = diametro / 2;
                    const innerRadius = outerRadius - espessura;
                    const geoExt = new THREE.CylinderGeometry(outerRadius, outerRadius, comprimento, segments, 1, true);
                    const geoInt = new THREE.CylinderGeometry(innerRadius, innerRadius, comprimento + 2, segments, 1, true);
                    mesh = new THREE.Mesh(geoExt, material);
                    let innerMesh = new THREE.Mesh(geoInt, materialInterno);
                    mesh.add(innerMesh);
                } else if (formato === 'cotovelo') {
                    const outerRadius = diametro / 2;
                    const innerRadius = outerRadius - espessura;
                    const curve = new THREE.QuadraticBezierCurve3(
                        new THREE.Vector3(0, 0, 0),
                        new THREE.Vector3(comprimento/2, 0, 0),
                        new THREE.Vector3(comprimento/2, comprimento/2, 0)
                    );
                    const geoExt = new THREE.TubeGeometry(curve, segments, outerRadius, 16, false);
                    const geoInt = new THREE.TubeGeometry(curve, segments, innerRadius, 16, false);
                    mesh = new THREE.Mesh(geoExt, material);
                    let innerMesh = new THREE.Mesh(geoInt, materialInterno);
                    mesh.add(innerMesh);
                } else if (formato === 't') {
                    const outerRadius = diametro / 2;
                    const innerRadius = outerRadius - espessura;
                    const geo1Ext = new THREE.CylinderGeometry(outerRadius, outerRadius, comprimento, segments, 1, true);
                    const geo1Int = new THREE.CylinderGeometry(innerRadius, innerRadius, comprimento + 2, segments, 1, true);
                    const geo2Ext = new THREE.CylinderGeometry(outerRadius, outerRadius, comprimento/2, segments, 1, true);
                    const geo2Int = new THREE.CylinderGeometry(innerRadius, innerRadius, comprimento/2 + 2, segments, 1, true);
                    let mesh1 = new THREE.Mesh(geo1Ext, material);
                    let mesh1Int = new THREE.Mesh(geo1Int, materialInterno);
                    mesh1.add(mesh1Int);
                    let mesh2 = new THREE.Mesh(geo2Ext, material);
                    let mesh2Int = new THREE.Mesh(geo2Int, materialInterno);
                    mesh2.add(mesh2Int);
                    mesh2.rotation.z = Math.PI/2;
                    mesh2.position.y = 0;
                    mesh2.position.x = 0;
                    mesh = new THREE.Group();
                    mesh.add(mesh1);
                    mesh2.position.z = 0;
                    mesh.add(mesh2);
                } else {
                    const outerRadius = diametro / 2;
                    const innerRadius = outerRadius - espessura;
                    const geoExt = new THREE.CylinderGeometry(outerRadius, outerRadius, comprimento, segments, 1, true);
                    const geoInt = new THREE.CylinderGeometry(innerRadius, innerRadius, comprimento + 2, segments, 1, true);
                    mesh = new THREE.Mesh(geoExt, material);
                    let innerMesh = new THREE.Mesh(geoInt, materialInterno);
                    mesh.add(innerMesh);
                }
                scene.add(mesh);
                meshes.push(mesh);
            }
        }

        function animate() {
            requestAnimationFrame(animate);
            renderer.render(scene, camera);
        }

        init3D();
        animate();

        function getFormParams() {
            return {
                diametro: parseFloat(document.getElementById('diametro').value) || 110,
                comprimento: parseFloat(document.getElementById('comprimento').value) || 1000,
                espessura: parseFloat(document.getElementById('espessura').value) || 3.6,
                formato: document.getElementById('formato').value,
                segments: parseInt(document.getElementById('segments').value) || 64,
                quantidade: parseInt(document.getElementById('quantidade').value) || 1
            };
        }

        document.getElementById('btnAtualizar').addEventListener('click', (e) => {
            e.preventDefault();
            criarCano(getFormParams());
        });

        document.getElementById('formato').addEventListener('change', () => {
            criarCano(getFormParams());
        });

        document.getElementById('btnReset').addEventListener('click', (e) => {
            e.preventDefault();
            camera.position.set(0, 0, 800);
            camera.up.set(0, 1, 0);
            camera.lookAt(0,0,0);
            meshes.forEach(mesh => {
                mesh.rotation.x = 0;
                mesh.rotation.y = 0;
                mesh.rotation.z = 0;
            });
        });

        document.getElementById('btnVisao3D').addEventListener('click', (e) => {
            e.preventDefault();
            camera.position.set(400, 400, 800);
            camera.up.set(0, 1, 0);
            camera.lookAt(0,0,0);
        });

        criarCano(getFormParams());
    }
});
