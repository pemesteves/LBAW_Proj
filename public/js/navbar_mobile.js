function show_pers_info() { 
    let navbar_pers_info = document.querySelector('#navbar_pers_info');
    let opacity = navbar_pers_info.style.opacity; 
    if(opacity == "1"){
        navbar_pers_info.style.opacity=0;
        navbar_pers_info.style.display="none";
    }
    else{
        navbar_pers_info.style.opacity=1;
        navbar_pers_info.style.display="block";
    }
} 