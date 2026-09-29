<div class="honor-marquee-box" role="region" aria-label="榮譽榜跑馬燈">
    <!-- 無障礙 HM1220200C 修正：將暫停按鈕放在組件的最前方，確保鍵盤 Tab 第一個聚焦 -->
    <button id="honor-toggle-btn" 
            class="honor-toggle-btn"
            type="button"
            aria-label="暫停跑馬燈">
        ⏸ <span class="sr-only">暫停</span>
    </button>

    <div class="honor-badge">
        🏆 榮譽榜
    </div>

    <div id="honor-container" class="honor-container">
        <div id="honor-content" class="honor-content">
            @foreach($honors as$honor)
                <div class="honor-item">
                    <a href="../posts/{{ $honor->id }}" class="honor-link">
                        🎉 {{ $honor->title }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
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