@extends('layouts.master_clean')

@section('title', '區塊顏色樣式 | ')

@section('content')
    <link href="{{ asset('css/block_style.css') }}" rel="stylesheet">
    <a href="#" onclick="history.back()" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 block1">
                <div class="title1">
                    <h4>單色綠</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
            <div class="col-md-3 block2">
                <div class="title2">
                    <h4>單色綠</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
            <div class="col-md-2 block3">
                <div class="title3">
                    <h4>單色黃</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
            <div class="col-md-2 block4">
                <div class="title4">
                    <h4>單色藍</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 soft-block1">
                <div class="soft-title1">
                    <h4>單色淺綠</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
            <div class="col-md-3 soft-block2">
                <div class="soft-title2">
                    <h4>單色淺黃</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
            <div class="col-md-2 soft-block3">
                <div class="soft-title3">
                    <h4>單色淺藍</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
            <div class="col-md-2 soft-block4">
                <div class="soft-title4">
                    <h4>單色淺紅</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 soft-block5">
                <div class="soft-title5">
                    <h4>單色淺淺藍</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
            <div class="col-md-3 soft-block6">
                <div class="soft-title6">
                    <h4>單色淺膚色<h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
            <div class="col-md-2 soft-block7">
                <div class="soft-title7">
                    <h4>單色淺淺黃</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
            <div class="col-md-2 soft-block8">
                <div class="soft-title8">
                    <h4>單色淺淺紅</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 gradient-block1">
                <div class="gradient-title1">
                    <h4>漸層紅</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
            <div class="col-md-3 gradient-block2">
                <div class="gradient-title2">
                    <h4>漸層藍</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
            <div class="col-md-2 gradient-block3">
                <div class="gradient-title3">
                    <h4>漸層綠</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
            <div class="col-md-2 gradient-block4">
                <div class="gradient-title4">
                    <h4>漸層黃</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet,
                        numquam animi? Pariatur expedita minus cumque!
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 default-block1">
                <div class="default-title1">
                    <h4>預設深灰</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Minus
                        nemo sapiente asperiores. Blanditiis nemo qui vel quasi nobis
                        distinctio laborum iusto, explicabo exercitationem suscipit
                        impedit aut quae autem in itaque minima, ut unde porro quod
                        corrupti. Mollitia perspiciatis voluptate suscipit cumque beatae
                        exercitationem ipsa dolores quia. Iusto quaerat odio rerum.
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 default-block2">
                <div class="default-title2">
                    <h4>預設淺灰</h4>
                </div>
                <div class="content2">
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Minus
                        nemo sapiente asperiores. Blanditiis nemo qui vel quasi nobis
                        distinctio laborum iusto, explicabo exercitationem suscipit
                        impedit aut quae autem in itaque minima, ut unde porro quod
                        corrupti. Mollitia perspiciatis voluptate suscipit cumque beatae
                        exercitationem ipsa dolores quia. Iusto quaerat odio rerum.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
