<div class="honor-marquee-box" style="
    display: flex; 
    align-items: center; 
    height: 45px; /* 垂直捲動建議高度大一點，視覺較舒適 */
    background: #fff5f5; 
    border: 2px solid #e3342f; 
    border-radius: 8px; 
    overflow: hidden; 
    position: relative;
    box-shadow: 4px 4px 0px #f8d7da;
    margin-bottom: 15px;
">
    <div style="
        background: #e3342f; 
        color: white; 
        padding: 0 20px; 
        height: 100%; 
        display: flex; 
        align-items: center; 
        font-weight: bold; 
        font-size: 1.2rem;
        z-index: 10;
        white-space: nowrap;
    ">
        🏆 榮譽榜
    </div>

    <div id="honor-container" style="
        flex: 1; 
        height: 100%; 
        position: relative; 
        overflow: hidden;
    ">
        <div id="honor-content" style="
            display: flex; 
            flex-direction: column; 
            align-items: flex-start; 
            padding-left: 15px;
        ">
            @foreach($honors as $honor)
                <div style="height: 80px; display: flex; align-items: center; white-space: nowrap;">
                    <a href="../posts/{{ $honor->id }}" style="
                        text-decoration: none; 
                        color: #333; 
                        font-weight: bold; 
                        font-size: 1.5rem; /* 大字體 */
                        transition: color 0.2s;
                    " onmouseover="this.style.color='#e3342f'" onmouseout="this.style.color='#333'">
                        🎉 {{ $honor->title }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<script>
window.onload = function() {
    const direction = "up";
    // 垂直捲動時，amount 要設得很小 (例如 0.5 ~ 1)，否則會像閃電一樣快
    const amount = 0.8; 

    const container = document.getElementById('honor-container');
    const content = document.getElementById('honor-content');

    if (!container || !content) return;

    // 基礎設定
    content.style.position = 'absolute';
    content.style.width = '100%';

    const containerHeight = container.offsetHeight;
    const contentHeight = content.offsetHeight;

    // 定義垂直動畫
    const animName = 'marqueeMoveHonorUp';
    const keyframes = `@keyframes ${animName} { 
        0% { transform: translateY(${containerHeight}px); } 
        100% { transform: translateY(-${contentHeight}px); } 
    }`;

    const style = document.createElement('style');
    style.innerHTML = keyframes;
    document.head.appendChild(style);

    // 垂直速度公式：(總高度) / (較小的倍率)
    const duration = (contentHeight + containerHeight) / (amount * 20);

    content.style.animation = `${animName} ${duration}s linear infinite`;

    // 滑鼠移入停止
    container.onmouseover = () => content.style.animationPlayState = 'paused';
    container.onmouseout = () => content.style.animationPlayState = 'running';
};
</script>