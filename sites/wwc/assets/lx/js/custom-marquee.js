$(function(){
    $('.de-marquee-list-1').marquee({
        direction: 'left',
        duration: 45000,
        gap: 48,
        delayBeforeStart: 0,
        duplicated: true,
        startVisible: true
    });

    $('.de-marquee-list-2').marquee({
        direction: 'right',
        duration: 45000,
        gap: 48,
        delayBeforeStart: 0,
        duplicated: true,
        startVisible: true
    });
});