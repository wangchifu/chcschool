<style>
/* 無障礙高對比 Focus 樣式與通用微調 */
.honor-marquee-box a:focus-visible,
.honor-marquee-box button:focus-visible {
    outline: 3px solid #ffed4a !important;
    outline-offset: 2px !important;
}
.honor-marquee-box a:hover {
    color: #e3342f !important;
}
</style>

<div class="honor-marquee-box" role="region" aria-label="榮譽榜跑馬燈" style="
    display: flex; 
    align-items: center; 
    height: 45px; 
    background: #fff5f5; 
    border: 2px solid #e3342f; 
    border-radius: 8px; 
    overflow: hidden; 
    position: relative;
    box-shadow: 4px 4px 0px #f8d7da;
    margin-bottom: 15px;
">
    <div style="background: #e3342f; color: white; padding: 0 15px; height: 100%; display: flex; align-items: center; font-weight: bold; font-size: 1.1rem; z-index: 10; white-space: nowrap;">
        🏆 榮譽榜
    </div>

    <div id="honor-container" style="flex: 1; height: 100%; position: relative; overflow: hidden;">
        <div id="honor-content" style="display: flex; flex-direction: row; align-items: center; height: 100%; white-space: nowrap;">
            @foreach($honors as $honor)
                <div style="margin-right: 40px; display: flex; align-items: center;">
                    <a href="../posts/{{ $honor->id }}" 
                       style="
                            text-decoration: none !important;
                            color: #2D3748; 
                            font-weight: bold; 
                            font-size: 1.1rem; 
                            white-space: nowrap;
                            transition: color 0.2s;
                       ">
                        🎉 {{ $honor->title }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 無障礙 HM1220200C 修正：提供實體控制按鈕（支援鍵盤操作與螢幕閱讀器） -->
    <button id="honor-toggle-btn" 
            type="button"
            aria-label="暫停跑馬燈"
            style="
                z-index: 10;
                background: #e3342f;
                color: #ffffff;
                border: none;
                height: 100%;
                padding: 0 12px;
                cursor: pointer;
                font-weight: bold;
                font-size: 0.9rem;
                display: flex;
                align-items: center;
                justify-content: center;
            ">
        ⏸ <span class="sr-only">暫停</span>
    </button>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const direction = "left";
    const amount = 1.2;

    const container = document.getElementById('honor-container');
    const content = document.getElementById('honor-content');
    const toggleBtn = document.getElementById('honor-toggle-btn');

    if (!container || !content) return;

    content.style.position = 'absolute';
    content.style.display = 'flex';
    content.style.flexDirection = 'row';
    content.style.whiteSpace = 'nowrap';

    const containerWidth = container.offsetWidth;
    const contentWidth = content.offsetWidth;
    const animName = 'marqueeMoveHonorLeft';
    
    const keyframes = `@keyframes ${animName} { 
        0% { transform: translateX(${containerWidth}px); } 
        100% { transform: translateX(-${contentWidth}px); } 
    }`;

    const style = document.createElement('style');
    style.innerHTML = keyframes;
    document.head.appendChild(style);

    const duration = (contentWidth + containerWidth) / (amount * 50);
    content.style.animation = `${animName} ${duration}s linear infinite`;

    let isPaused = false;

    function pauseMarquee() {
        content.style.animationPlayState = 'paused';
        if(toggleBtn) {
            toggleBtn.innerHTML = '▶ <span class="sr-only">播放</span>';
            toggleBtn.setAttribute('aria-label', '繼續播放跑馬燈');
        }
        isPaused = true;
    }

    function playMarquee() {
        content.style.animationPlayState = 'running';
        if(toggleBtn) {
            toggleBtn.innerHTML = '⏸ <span class="sr-only">暫停</span>';
            toggleBtn.setAttribute('aria-label', '暫停跑馬燈');
        }
        isPaused = false;
    }

    // 滑鼠移入懸停暫停
    container.onmouseover = () => { if(!isPaused) content.style.animationPlayState = 'paused'; };
    container.onmouseout = () => { if(!isPaused) content.style.animationPlayState = 'running'; };

    // 鍵盤焦點進入跑馬燈連結時自動暫停，離開時恢復（保護鍵盤使用者）
    const links = content.querySelectorAll('a');
    links.forEach(link => {
        link.addEventListener('focus', () => content.style.animationPlayState = 'paused');
        link.addEventListener('blur', () => { if(!isPaused) content.style.animationPlayState = 'running'; });
    });

    // 點擊按鈕切換暫停/播放
    if(toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            if (content.style.animationPlayState === 'paused' && isPaused) {
                playMarquee();
            } else {
                pauseMarquee();
            }
        });
    }
});
</script>