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
// 改用 DOMContentLoaded，這樣 HTML 一出來就立刻動，不會等圖片
document.addEventListener("DOMContentLoaded", function() {
    const direction = "up";
    const amount = 0.8; 

    const container = document.getElementById('honor-container');
    const content = document.getElementById('honor-content');

    if (!container || !content) return;

    // --- 優化點 1: 在計算前先給予基本樣式，避免閃爍 ---
    content.style.position = 'absolute';
    content.style.width = '100%';
    content.style.display = 'flex';
    content.style.flexDirection = 'column';

    // --- 優化點 2: 使用 offsetHeight 抓取當前高度 ---
    // 如果這裡抓不到高度，通常是因為裡面的 div 還沒撐開
    const containerHeight = container.offsetHeight || 45; // 沒抓到就用預設 45
    const contentHeight = content.offsetHeight;

    const animName = 'marqueeMoveHonorUp';
    const keyframes = `@keyframes ${animName} { 
        0% { transform: translateY(${containerHeight}px); } 
        100% { transform: translateY(-${contentHeight}px); } 
    }`;

    const style = document.createElement('style');
    style.innerHTML = keyframes;
    document.head.appendChild(style);

    // --- 優化點 3: 確保速度穩定 ---
    const duration = (contentHeight + containerHeight) / (amount * 20);

    // 立刻套用動畫
    content.style.animation = `${animName} ${duration}s linear infinite`;

    // 滑鼠移入停止
    container.onmouseover = () => content.style.animationPlayState = 'paused';
    container.onmouseout = () => content.style.animationPlayState = 'running';
});
</script>